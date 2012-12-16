<?php

/**
 * This is the model class for table "event".
 *
 * The followings are the available columns in table 'event':
 * @property string $id
 * @property string $userId
 * @property string $dateTime
 * @property string $title
 * @property string $venue
 * @property string $shortTitle
 * @property integer $status
 * @property string $location
 * @property string $latitude
 * @property string $longitude
 * @property string $description
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Event extends CActiveRecord
{
	const STATUS_CONFIRMED = 0;
	const STATUS_CANCELED  = 1;
	const STATUS_TBA       = 2;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Event the static model class
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
		return 'event';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userId, status', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('userId', 'length', 'max'=>20),
			array('shortTitle', 'length', 'max'=>50),
			array('latitude, longitude', 'length', 'max'=>18),
			array('dateTime, title, venue, location, description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('userId, dateTime, title, venue, shortTitle, location, description', 'safe', 'on'=>'search'),
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
			'id' => 'Event ID',
			'userId' => 'User',
			'dateTime' => 'Date',
			'title' => 'Title',
			'venue' => 'Venue',
			'shortTitle' => 'Short Title',
			'status' => 'Status',
			'location' => 'Location',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
			'description' => 'Description',
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

		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('dateTime',$this->dateTime,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('venue',$this->venue,true);
		$criteria->compare('shortTitle',$this->shortTitle,true);
		$criteria->compare('location',$this->location,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getStatusTypes() {
		return array (
			self::STATUS_CONFIRMED => 'Confirmed',
			self::STATUS_CANCELED  => 'Canceled',
			self::STATUS_TBA       => 'To Be Announced',
		);
	}
	
	public function getStatusAsText() {
		$statusArray = self::getStatusTypes();
		return $statusArray[$this->status];
	}
	
	public function afterSave() {
		$tokens = Token::model()->findAllByAttributes(array('userId' => $this->userId));
		
		foreach($tokens as $token) {
			
			switch($token->serviceType) {
				case Token::CONNECTION_SOUNDCLOUD:
					$this->updateSoundCloud($token); break;
				case Token::CONNECTION_TWITTER:
					// we only update twitter when we are adding a new event
					if ($this->getIsNewRecord()) {
						$this->updateTwitter($token); break;
					}
			}
			//var_dump($token);
			//echo "<br /><br />";
		}
		
		//echo "lalal";
		
		parent::afterSave();
	}
	
	public static function updateSoundCloud($token) {

		if ($token->serviceType != Token::CONNECTION_SOUNDCLOUD)
			throw new CHttpException(400, 'Soundcloud update error: this is not a Soundcloud token.');
		
		$soundcloud = Token::createSoundCloudInstance();
			
		$soundcloud->setAccessToken($token->tToken);
		
		$listWidgetAbsUrl = Yii::app()->createAbsoluteUrl('event/listWidget', array('userId' => $token->userId));
		$newDesc = $token->user->biography . "\n\n" . CHtml::link('Events', $listWidgetAbsUrl) . ':';
	
		//$yesterday = strtotime("yesterday 00:00");
		//$yesterdayString = date("Y-m-d H:i:s", $yesterday);
		
		$events = Event::model()->findAll(array(
			'condition' => 'userId = :userId AND dateTime > DATE_ADD(NOW(), INTERVAL -1 DAY) AND status = ' . Event::STATUS_CONFIRMED,
			'params' => array(':userId'=>$token->userId),
		));
	
		foreach ($events as $event) {
			$eventAbsUrl = Yii::app()->createAbsoluteUrl('event/viewGigWidget', array('id' => $event->id));
			$newDesc .= "\n" . Yii::app()->dateFormatter->format('d/L', $event->dateTime) . ' : ' .
				CHtml::link(CHtml::encode($event->shortTitle), $eventAbsUrl) .
				" @ " . $event->venue;
		}
		$newDesc = CHtml::encode($newDesc);
		$descMsg = "
			<user>
			<description>".$newDesc."</description>
			</user>
		";

		//echo "descMsg: " . $descMsg;
		try {
			$msg = $soundcloud->put('me', $descMsg, array(CURLOPT_HTTPHEADER => array('Content-Type: application/xml')));
			//var_dump($msg);
			$response = json_decode($msg, true);
			//echo "<br />Update successful<br />";
			//var_dump($response);
		} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
			throw new CHttpException(400, 'Soundcloud update error ' . $e->getMessage() . '.');
		}
	}
	
	private function updateTwitter($token) {
		
		$twitterOAuth = TwitterOAuth::createTwitterOAuthHandler($token->tToken, $token->tTokenSecret);
		
		$dateText = Yii::app()->dateFormatter->format('d/L', $this->dateTime);
		$eventLink = Yii::app()->createAbsoluteUrl('event/viewGigWidget', array('id' => $this->id));
		
		$tweet = "New event: " . $dateText . " : " . $this->shortTitle . " - " . $this->venue .
				" (" . $eventLink . ")";
		
		$response = $twitterOAuth->send_tweet($tweet);
		
		if (is_null($response)) {
			throw new CHttpException(400, 'Twitter update error:\n' . $twitterOAuth->outputError() . '.');
		}
		else {
			$reply = json_decode($response);
			//echo "<br />Update successful<br />";
		}
	}
}