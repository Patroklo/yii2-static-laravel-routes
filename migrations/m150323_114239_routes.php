<?php

use yii\db\Migration;
use yii\db\Schema;

class m150323_114239_routes extends Migration
{

	public function tableName()
	{
		return \cyneek\yii2\routes\Module::$tableName;
	}

	public function safeUp()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		$this->createTable($this->tableName(), [
			'id' => Schema::TYPE_PK,
			'type' => Schema::TYPE_STRING . '(25) NOT NULL',
			'uri' => Schema::TYPE_STRING . '(255) NOT NULL',
			'route' => Schema::TYPE_STRING . '(255) NOT NULL',
			'config' => Schema::TYPE_TEXT,
			'app' => Schema::TYPE_STRING . '(50) NOT NULL',
		], $tableOptions);

		// add indexes for performance optimization
		$this->createIndex('{{%site_routes_type_app}}', $this->tableName(), ['type', 'app'], true);
		$this->createIndex('{{%site_routes_app}}', $this->tableName(), ['app'], false);

	}

	public function safeDown()
	{
		$this->dropTable($this->tableName());
	}
}
