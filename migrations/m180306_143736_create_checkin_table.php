<?php

use yii\db\Migration;

class m180306_143736_create_checkin_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function up()
	{
		$this->createTable('{{%checkin}}', [
			'id' => $this->primaryKey(),
			'id_client' => $this->integer()->notNull(),
			'checkin' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
			'last' => $this->boolean()->notNull()->defaultValue(false),
		]);

		// chave estrangeira
		$this->addForeignKey('fk_client_checkin', '{{%checkin}}', 'id_client', '{{%client}}', 'id', 'CASCADE', 'CASCADE');
	}

	/**
	 * {@inheritdoc}
	 */
	public function down()
	{
		$this->dropTable('{{%checkin}}');
	}
}
