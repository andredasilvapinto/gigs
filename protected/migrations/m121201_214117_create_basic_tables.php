<?php

class m121201_214117_create_basic_tables extends CDbMigration
{
	public function safeUp()
	{
		$this->createTable('user', array(
			'id'			=> 'bigint(20) unsigned NOT NULL AUTO_INCREMENT',
			'name'			=> 'varchar(60) NOT NULL',
			'password'		=> 'varchar(64) NOT NULL',
			'email'			=> 'varchar(100) NOT NULL',
			'status'		=> 'tinyint(4) NOT NULL',
			'artistName'	=> 'string',
			'imageURL'		=> 'varchar(2083) DEFAULT NULL',
			'biography'		=> 'text',
			'bookingContact'=> 'varchar(2083) DEFAULT NULL',
			'PRIMARY KEY (`id`)'
		), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8');

		$this->createTable('event', array(
            'id'			=> 'bigint(20) unsigned NOT NULL AUTO_INCREMENT',
            'userId'		=> 'bigint(20) unsigned NOT NULL',
			'dateTime'		=> 'datetime DEFAULT NULL',
            'title'			=> 'text',
			'venue'			=> 'text',
			'shortTitle'	=> 'varchar(50) DEFAULT NULL',
			'status'		=> 'tinyint(4) NOT NULL',
			'location'		=> 'text',
			'latitude'		=> 'decimal(18,15) DEFAULT NULL',
			'longitude'		=> 'decimal(18,15) DEFAULT NULL',
			'description'	=> 'text',
			'PRIMARY KEY (`id`)'
        ), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8');
		
		$this->createTable('token', array(
			'id'			=> 'bigint(20) unsigned NOT NULL AUTO_INCREMENT',
			'userId'		=> 'bigint(20) unsigned NOT NULL',
			'serviceType'	=> 'tinyint(4) unsigned NOT NULL',
			'token'			=> 'text',
			'tokenSecret'	=> 'text',
			'PRIMARY KEY (`id`)'
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
	}

	public function safeDown()
	{
		$this->dropTable('user');
		$this->dropTable('event');
		$this->dropTable('token');
	}
}