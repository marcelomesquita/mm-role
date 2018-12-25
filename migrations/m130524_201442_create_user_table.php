<?php

use yii\db\Migration;

class m130524_201442_create_user_table extends Migration
{
	public $email = 'contato@marcelomesquita.com';
	public $name = 'Marcelo Mesquita';
	public $password = '123456';

	/**
	 * {@inheritdoc}
	 */
	public function up()
	{
		$this->createTable('{{%user}}', [
			'id' => $this->primaryKey(),
			'email' => $this->string()->notNull()->unique(),
			'name' => $this->string()->notNull(),
			'auth_key' => $this->string()->notNull(),
			'password_hash' => $this->string(),
			'password_reset_token' => $this->string()->unique(),
			'role' => $this->smallInteger()->notNull()->defaultValue(0),
		]);

		// índice
		$this->createIndex('un_usuario', '{{%user}}', 'email', true);

		// alimentar
		$this->batchInsert('{{%user}}', ['email', 'name', 'auth_key', 'password_hash', 'role'], [
			[$this->email, $this->name, Yii::$app->security->generateRandomString(), Yii::$app->getSecurity()->generatePasswordHash($this->password), 10]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function down()
	{
		$this->dropTable('{{%user}}');
	}
}
