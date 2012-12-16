<?php

class EventController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'eventsContext - create, index, listWidget, viewGigWidget'
		);
	}
	
	//TODO: Remover isto? o que raio faz?
	//only show/edit/remove my events
	public function filterEventsContext($filterChain)
	{
		$res = Event::model()->findByAttributes(array('userId' => Yii::app()->user->id));
		if ($res === null)
			throw new CHttpException("You can't acces s this event");
			
		$filterChain->run();
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('listWidget','viewGigWidget'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','delete','index','view'),
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
	
	public function actionListWidget($userId)
	{		
		$user = User::model()->findByPk($userId);
		
		if (is_null($user)) {
			throw new CHttpException('Could not find specified userid.');
		}
		else {
			$dataProvider = new CActiveDataProvider('Event', array(
				'criteria'=>array(
					'condition' => 'userId = :userId AND dateTime > DATE_ADD(NOW(), INTERVAL -1 DAY) AND status = ' . Event::STATUS_CONFIRMED,
					'params' => array(':userId'=>$user->id),
				),
				'pagination'=>array(
					'pageSize'=>10,
				),
			));
			
			$this->render('listWidget',array(
				'user'=> $user,
				'dataProvider'=>$dataProvider,
			));
		}
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{			
		$model = $this->loadModel($id);
		if ($model->userId != Yii::app()->user->id)
			throw new CHttpException("You can't access this event");
		
		$dataProvider=new CActiveDataProvider('Event', array(
			'criteria'=>array(
				'condition'=>'userId=:userId',
				'params'=>array(':userId'=>Yii::app()->user->id),
			),
			'pagination'=>array(
				'pageSize'=>1,
			),
		));
    
		$this->render('view',array(
			'model'=>$this->loadModel($id),
			'dataProvider'=>$dataProvider,
		));
	}
	
	public function actionViewGigWidget($id)
	{
		$this->render('viewGigWidget',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Event;
		
		$model->userId = Yii::app()->user->id;
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Event']))
		{
			$model->attributes=$_POST['Event'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		} else {
			// default arguments
			$model->status = 1;
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if ($model->userId != Yii::app()->user->id)
			throw new CHttpException("You can't access this event");

		if(isset($_POST['Event']))
		{
			$model->attributes=$_POST['Event'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
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
				throw new CHttpException("You can't access this event");
			
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
		/*$dataProvider=new CActiveDataProvider('Event');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));*/
		
		
		$dataProvider=new CActiveDataProvider('Event', array(
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
		$model=new Event('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Event']))
			$model->attributes=$_GET['Event'];

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
		$model=Event::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='event-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
