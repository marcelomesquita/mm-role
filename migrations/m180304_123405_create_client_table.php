<?php

use yii\db\Migration;

class m180304_123405_create_client_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function up()
	{
		$this->createTable('{{%client}}', [
			'id' => $this->primaryKey(),
			'name' => $this->string()->notNull(),
			'phone' => $this->string(),
			'email' => $this->string(),
			'sex' => $this->integer(),
			'birthday' => $this->date(),
			'cpf' => $this->string(11)->notNull(),
			'cop' => $this->boolean(),
			'cop_institution' => $this->integer(),
			'cop_register' => $this->integer(),
			'cop_weapon' => $this->string(),
		]);

		// índice
		$this->createIndex('un_client', '{{%client}}', 'cpf', true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function down()
	{
		$this->dropTable('{{%client}}');
	}
}
