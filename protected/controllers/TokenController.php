<?php

class TokenController extends Controller
{	
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	
	/*public function actions()
    {
        return array(
            'auth'=>'application.controllers.TokenController',
        );
    }*/
	
	
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow everybody to perform the cron job soundcloud updates
				'actions'=>array('cronRefreshSoundcloud'),
				'users'=>array('*')
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index', 'view', 'create', 'delete', 'authTwitter', 'authSoundcloud', 'refreshSoundcloud'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{	
		$model = $this->loadModel($id);
		if ($model->userId != Yii::app()->user->id)
			throw new CHttpException("You can't access this connection");
		
		// show tokens only to their owners
		$dataProvider=new CActiveDataProvider('Token', array(
			'criteria'=>array(
				'condition'=>'userId=:userId',
				'params'=>array(':userId'=>Yii::app()->user->id),
			),
			'pagination'=>array(
				'pageSize'=>1,
			),
		));
    
		$this->render('view',array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new Token;

		$model->userId = Yii::app()->user->id;
					
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Token']))
		{
			$model->attributes=$_POST['Token'];
					
			foreach($model->user->tokens as $token) {
				if ($token->serviceType == $model->serviceType)
					throw new CHttpException("You already have a Token of that type");
			}
			
			switch($model->serviceType) {
				case Token::CONNECTION_SOUNDCLOUD:
					$this->connectToSoundCloud(); return;
				case Token::CONNECTION_TWITTER:
					$this->connectToTwitter(); return;
				case Token::CONNECTION_HTML:
					throw new CHttpException("Bad request");
				
				// OAuth tokens are only saved at the end of the authentication process
				default:
					$model->save();
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	public function actionAuthTwitter()	{	
		
		if(isset($_REQUEST['oauth_verifier'])) {
			
			$twitterOAuth = TwitterOAuth::createNewOAuthTwitter();
			
			$response = $twitterOAuth->access_token();
			
			if (is_null($response)) {
				throw new CHttpException($twitterOAuth->outputError());
			}
			else {
				$model = new Token;
				$model->userId = Yii::app()->user->id;
				$model->serviceType = Token::CONNECTION_TWITTER;
				$model->token = $response["oauth_token"];
				$model->tokenSecret = $response["oauth_token_secret"];
				
				$model->save();
				
				$this->redirect(array('index'));
			}
		}
		else {
			throw new CHttpException("Bad request");
		}
	}
	
	public function actionAuthSoundcloud() {
		//echo "Welcome Back";
		//echo "State: " . $state . "<br/>";
		//var_dump($_GET);
		
		// we are using "code" response type
		if (isset($_GET['code'])) {
			// code, access_token (not in "code" response type mode), [expires_in],
			// [scope] (required if different than requested), state

			$code = $_GET['code'];

			//TODO: check if it is different than non-expiring
			if (isset($_GET['scope']))
				$scope = $_GET['scope'];
			
			//echo "Code: " . $code . "<br/>";

			$model = new Token;
			$model->userId = Yii::app()->user->id;
			$model->serviceType = Token::CONNECTION_SOUNDCLOUD;

			$soundcloud = Token::createSoundCloudInstance();

			try {
				// access_token, [expires_in], [refresh_token], [scope]
				$responseToken = $soundcloud->accessToken($code, array('scope'=>'non-expiring'));
				
				$accessToken = $responseToken['access_token'];
				
				//echo "Access Token: " . $accessToken . "<br />";
				
				if (isset($responseToken['scope']) && strcmp($responseToken['scope'], 'non-expiring') != 0) {
					echo "ERROR - wrong token's scope";
				}
				
				//var_dump($responseToken);
				try {
					$response = json_decode($soundcloud->get('me'), true);
					//echo "Welcome back ".$response["username"]."</br>";
					//echo "Current description:<br/>";
					//echo $response["description"]."<br/>";

					// soundcloud oauth implementation doesn't use Access Token Secret
					$model->tToken = $accessToken;
					//$model->tTokenSecret = $accessToken;
					
					$model->save();
					
					$this->redirect(array('index'));
					
				} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
					throw new CHttpException(400, 'Auth error ' . $e->getMessage() . '.');
				}
			} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
				throw new CHttpException(400, 'Auth error ' . $e->getMessage() . '.');
			}		
		}
		else {
			//error, [error_description], [error_uri], state
			throw new CHttpException(400, 'Auth error ' . isset($_GET['error'])?$_GET['error']:'' . '.');
		}
			
	}
	
	function actionRefreshSoundcloud($id)
	{
		$model = $this->loadModel($id);
		if ($model->userId != Yii::app()->user->id)
			throw new CHttpException("You can't access this connection");
		
		Event::updateSoundCloud($model);
		
		//$this->redirect(array('view', 'id' => $id));
		$this->redirect(array('index'));
	}
	
	function actionCronRefreshSoundcloud()
	{
		$tokens = Token::model()->findAllByAttributes(array('serviceType' => Token::CONNECTION_SOUNDCLOUD));
		
		foreach($tokens as $token)
		{
			Event::updateSoundCloud($token);
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{	
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$model = $this->loadModel($id);

			if ($model->userId != Yii::app()->user->id)
				throw new CHttpException("You can't access this connection");
		
			$model->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Token', array(
			'criteria'=>array(
				'condition'=>'userId=:userId',
				'params'=>array(':userId'=>Yii::app()->user->id),
			)
		));
    
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Token('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Token']))
			$model->attributes=$_GET['Token'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Token::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='token-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	
	public function connectToSoundCloud()
	{
		$soundcloud = Token::createSoundCloudInstance();
		
		//$authorizeURL = $soundcloud->getAuthorizeUrl(array('state' => $id, 'scope' => 'non-expiring'));
		$authorizeURL = $soundcloud->getAuthorizeUrl(array('scope' => 'non-expiring'));
		
		//echo CHtml::link("Link", $authorizeURL);
		//echo CHtml::decode($authorizeURL);
		
		$this->redirect($authorizeURL);
		
		//$this->redirect(array('view','id'=>$model->id));
	}
	
	public function connectToTwitter()
	{	
		$twitterOAuth = TwitterOAuth::createNewOAuthTwitter();
	
		$callbackURL = Yii::app()->createAbsoluteUrl('token/authTwitter');
		
		$twitterOAuth->request_token($callbackURL);
	}
}
