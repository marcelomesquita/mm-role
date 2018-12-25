<?php

use yii\db\Migration;

class m180319_131509_alter_client_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function up()
	{
		$this->alterColumn('{{%client}}', 'cpf', $this->string(11)->null());
		$this->addColumn('{{%client}}', 'foreign', $this->boolean());
		$this->addColumn('{{%client}}', 'foreign_register', $this->string());
		$this->addColumn('{{%client}}', 'foreign_register_type', $this->integer());

		// índice
		$this->dropIndex('un_client', '{{%client}}');
	}

	/**
	 * {@inheritdoc}
	 */
	public function down()
	{
		$this->dropColumn('{{%client}}', 'foreign');
		$this->dropColumn('{{%client}}', 'foreign_register');
		$this->dropColumn('{{%client}}', 'foreign_register_type');
	}
}
