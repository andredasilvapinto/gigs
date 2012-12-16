<?php

class m121202_004221_add_data_basic_tables extends CDbMigration
{
	public function safeUp()
	{
		$this->insert('user', array(
			'name'			=> 'admin',
			'password'		=> '$P$BB0yuq.aYIbS7H62HKd9hMJ28iS3da/',
			'email'			=> 'webmaster@sobreira.net',
			'status'		=> 1,
			'artistName'	=> 'nome artistico',
			'imageURL'		=> 'http://www.primates.com/chimps/chimpanzee-picture.jpg',
			'biography'		=> 'Katrapunga, Lorem-ipsum pá piroca!',
			'bookingContact'=> 'atuatia@novirardaesquina.come'
		));
		
		$this->insert('user', array(
			'name'			=> 'teste',
			'password'		=> '$P$BB0yuq.aYIbS7H62HKd9hMJ28iS3da/',
			'email'			=> 'teste@askcao.sd',
			'status'		=> 1,
			'artistName'	=> 'teste',
			'imageURL'		=> '',
			'biography'		=> 'main genres: techno, progressive and electronica. check out more sets (too big for soundcloud\'s free account) in the links above.',
			'bookingContact'=> ''
		));
	}

	public function safeDown()
	{
		$this->delete('user', 'name = :name', array(':name' => 'admin'));
		$this->delete('user', 'name = :name', array(':name' => 'teste'));
	}
}