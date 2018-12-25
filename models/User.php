<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $email
 * @property string $name
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property integer $role
 *
 * @property string $file write-only
 * @property string $checkin write-only
 * @property string $employee write-only
 * @property string $password_confirmation write-only
 * @property string $password_confirmation write-only
 */
class User extends ActiveRecord implements IdentityInterface
{
	// roles
	const ROLE_USER = 5;
	const ROLE_ADMINISTRATOR = 10;

	const ROLES = [self::ROLE_USER => 'usuário', self::ROLE_ADMINISTRATOR => 'administrador'];

	// write-only atributtes
	public $checkin;
	public $password;
	public $password_confirmation;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return '{{%user}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		$scenarios = parent::scenarios();

		$scenarios['forget'] = ['email'];
		$scenarios['reset'] = ['password', 'password_confirmation', 'password_reset_token'];
		$scenarios['create'] = ['email', 'name', 'password', 'password_confirmation', 'role'];
		$scenarios['update'] = ['email', 'name', 'password', 'password_confirmation', 'role'];
		$scenarios['profile'] = ['email', 'name', 'password', 'password_confirmation'];

		return $scenarios;
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['password', 'password_confirmation'], 'required', 'on' => 'reset'],
			[['email'], 'required', 'on' => 'forget'],
			[['email', 'name', 'file'], 'required', 'on' => 'create'],
			[['email', 'name'], 'required', 'on' => 'update'],
			[['email', 'name', 'password', 'password_confirmation'], 'string'],
			[['role'], 'integer'],
			[['email'], 'email'],
			[['email'], 'unique'],
			[['role'], 'default', 'value' => self::ROLE_USER],
			[['password_confirmation'], 'compare', 'compareAttribute' => 'password'],
			['role', function($attribute, $params) {
				if($this->role == User::ROLE_ADMINISTRATOR and Yii::$app->user->identity->role !== User::ROLE_ADMINISTRATOR) {
					$this->addError($attribute, 'Seu perfil não está autorizado para conceder esse acesso.');
				}
			}],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'email' => 'E-mail',
			'name' => 'Nome',
			'password' => 'Senha',
			'password_confirmation' => 'Confirmar Senha',
			'role' => 'Função',
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function findIdentity($id)
	{
		return static::findOne(['id' => $id]);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function findIdentityByAccessToken($token, $type = null)
	{
		throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
	}

	/**
	 * Finds user by email
	 *
	 * @param string $email
	 * @return static|null
	 */
	public static function findIdentityByEmail($email)
	{
		return static::findOne(['email' => $email]);
	}

	/**
	 * Finds user by password reset token
	 *
	 * @param string $password_reset_token password reset token
	 * @return static|null
	 */
	public static function findIdentityByPasswordResetToken($password_reset_token)
	{
		if (!static::validatePasswordResetToken($password_reset_token)) {
			return null;
		}

		return static::findOne(['password_reset_token' => $password_reset_token]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function validateAuthKey($auth_key)
	{
		return $this->auth_key === $auth_key;
	}

	/**
	 * Validates password
	 *
	 * @param string $password password to validate
	 * @return bool if password provided is valid for current user
	 */
	public function validatePassword($password)
	{
		return Yii::$app->security->validatePassword($password, $this->password_hash);
	}

	/**
	 * Finds out if password reset token is valid
	 *
	 * @param string $token password reset token
	 * @return bool
	 */
	public static function validatePasswordResetToken($password_reset_token)
	{
		if (empty($password_reset_token)) {
			return false;
		}

		$timestamp = (int) substr($password_reset_token, strrpos($password_reset_token, '_') + 1);
		$expire = Yii::$app->params['passwordResetTokenExpire'];

		return $timestamp + $expire >= time();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAuthKey()
	{
		return $this->auth_key;
	}

	/**
	 * Generates "remember me" authentication key
	 */
	public function generateAuthKey()
	{
		$this->auth_key = Yii::$app->security->generateRandomString();
	}

	/**
	 * Generates password hash from password and sets it to the model
	 *
	 * @param string $password
	 */
	public function generatePasswordHash($password)
	{
		$this->password_hash = Yii::$app->security->generatePasswordHash($password);
	}

	/**
	 * Generates new password reset token
	 */
	public function generatePasswordResetToken()
	{
		$this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
	}

	/**
	 * Removes password reset token
	 */
	public function removePasswordResetToken()
	{
		$this->password_reset_token = null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function beforeSave($insert)
	{
		if (!parent::beforeSave($insert)) {
			return false;
		}

		if ($insert) {
			$this->generateAuthKey();
		}

		if (!empty($this->password)) {
			$this->generatePasswordHash($this->password);
		}

		return true;
	}
}
