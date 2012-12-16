<?php

class m121202_134246_set_basic_table_foreign_keys extends CDbMigration
{
	public function safeUp()
	{
		$this->addForeignKey('event_user_FK', 'event', 'userId', 'user', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('token_user_FK', 'token', 'userId', 'user', 'id', 'CASCADE', 'CASCADE');
	}

	public function safeDown()
	{
		$this->dropForeignKey('event_user_FK', 'event');
		$this->dropForeignKey('token_user_FK', 'token');
	}
}