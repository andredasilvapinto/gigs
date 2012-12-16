<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $id
 * @property string $name
 * @property string $password
 * @property string $email
 * @property integer $status
 * @property string $artistName
 * @property string $imageURL
 * @property string $biography
 * @property string $bookingContact
 *
 * The followings are the available model relations:
 * @property Event[] $events
 * @property Token[] $tokens
 */
class User extends CActiveRecord
{	
	const TYPE_INACTIVE = 0;
	const TYPE_ACTIVE   = 1;
	
	public $new_password;
	public $new_password_repeat;

	
	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, password, email, status, artistName', 'required'),
			array('name, email', 'unique'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>60),
			array('password', 'length', 'max'=>64),
			array('email', 'length', 'max'=>100),
			array('email', 'email'), // email has to be a valid email address
			array('artistName', 'length', 'max'=>255),
			array('imageURL, bookingContact', 'length', 'max'=>2083),
			array('biography', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('name, email, artistName, biography, bookingContact', 'safe', 'on'=>'search'),
			
			
		    array('new_password', 'length', 'max'=>64),
		    array('new_password', 'compare', 'on'=>'newPassword'),
			array('new_password_repeat', 'safe'),
			array('new_password, new_password_repeat', 'required', 'on'=>'insert'),
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
			'events' => array(self::HAS_MANY, 'Event', 'userId'),
			'tokens' => array(self::HAS_MANY, 'Token', 'userId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'User ID',
			'name' => 'Name',
			'password' => 'Password',
			'email' => 'Email',
			'status' => 'Status',
			'artistName' => 'Artist Name',
			'imageURL' => 'Image Url',
			'biography' => 'Biography',
			'bookingContact' => 'Booking Contact',
			'new_password' => 'Password',
			'new_password_repeat' => 'Repeat Password',
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

		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('artistName',$this->artistName,true);
		$criteria->compare('biography',$this->biography,true);
		$criteria->compare('bookingContact',$this->bookingContact,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function hashPassword($password) {
		$ph = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
		return $ph->HashPassword($password);
	}
	
	public function beforeSave()
	{
		if(!empty($this->new_password) && $this->getScenario() === 'newPassword')
		{
			$this->password = $this->hashPassword($this->password);
		}
		return parent::beforeSave();
	}
	
	public function getStatusTypes() {
		return array (
			self::TYPE_INACTIVE => 'Inactive',
			self::TYPE_ACTIVE => 'Active',
		);
	}
	
	public function getStatusAsText() {
		$statusArray = self::getStatusTypes();
		return $statusArray[$this->status];
	}
}