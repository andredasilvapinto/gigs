<?php

/**
 * This is the model class for table "token".
 *
 * The followings are the available columns in table 'token':
 * @property string $id
 * @property string $userId
 * @property integer $serviceType
 * @property string $token
 * @property string $tokenSecret
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Token extends CActiveRecord
{
	const CONNECTION_SOUNDCLOUD = 0;
	const CONNECTION_TWITTER    = 1;
	const CONNECTION_HTML       = 2;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Token the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'token';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userId, serviceType', 'required'),
			array('serviceType', 'numerical', 'integerOnly'=>true),
			array('userId', 'length', 'max'=>20),
			array('token, tokenSecret', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, userId, serviceType, token, tokenSecret', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'userId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'userId' => 'User',
			'serviceType' => 'Service Type',
			'token' => 'Token',
			'tokenSecret' => 'Token Secret',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('serviceType',$this->serviceType);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('tokenSecret',$this->tokenSecret,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function afterSave() {

		if ($this->serviceType == Token::CONNECTION_SOUNDCLOUD)
		{
			Event::updateSoundCloud($this);
		}

		parent::afterSave();
	}
	
	public function getConnectionTypes() {
		return array(
			self::CONNECTION_SOUNDCLOUD => "Soundcloud",
			self::CONNECTION_TWITTER => "Twitter",
			self::CONNECTION_HTML => "Others (HTML)",
		);
	}
	
	public function getAvailableConnectionTypes() {
		$availableTypes = $this->getConnectionTypes();
				
		foreach ($this->user->tokens as $token) {
			unset($availableTypes[$token->serviceType]);
		}
		
		return $availableTypes;
	}
	
	public function getConnectionAsText() {
		$connectionsArray = self::getConnectionTypes();
		return $connectionsArray[$this->serviceType];
	}
	
	public static function createSoundCloudInstance() {
		$callbackURL = Yii::app()->createAbsoluteUrl('token/authSoundcloud');

		$soundcloud = new Services_Soundcloud(
			GigsConfig::SOUNDCLOUD_APP_OAUTH_CLIENT_ID,
			GigsConfig::SOUNDCLOUD_APP_OAUTH_CLIENT_SECRET,
			$callbackURL
		);

		return $soundcloud;
	}
}