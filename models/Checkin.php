<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%checkin}}".
 *
 * @property int $id
 * @property int $id_client
 * @property string $checkin
 *
 * @property Client $client
 */
class Checkin extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%checkin}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id_client', 'checkin', 'last'], 'required'],
			[['id_client'], 'integer'],
			[['checkin'], 'date', 'format' => 'yyyy-M-d H:m:s'],
			[['last'], 'boolean'],
			[['id_client'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['id_client' => 'id']],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'id_client' => 'Usuário',
			'checkin' => 'Checkin',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getClient()
	{
		return $this->hasOne(Client::className(), ['id' => 'id_client']);
	}
}
