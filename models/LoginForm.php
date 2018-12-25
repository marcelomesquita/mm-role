<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
	public $email;
	public $password;
	public $remember = true;

	private $_user = false;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			[['email', 'password'], 'required'],
			[['email'], 'email'],
			['password', 'validatePassword'],
			['remember', 'boolean'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'email' => 'E-mail',
			'password' => 'Senha',
			'remember' => 'Lembrar',
		];
	}

	/**
	 * Validates the password.
	 * This method serves as the inline validation for password.
	 *
	 * @param string $attribute the attribute currently being validated
	 * @param array $params the additional name-value pairs given in the rule
	 */
	public function validatePassword($attribute, $params)
	{
		if (!$this->hasErrors()) {
			$user = $this->getUser();

			if (!$user || !$user->validatePassword($this->password)) {
				$this->addError($attribute, 'Incorrect username or password.');
			}
		}
	}

	/**
	 * Logs in a user using the provided username and password.
	 *
	 * @return bool whether the user is logged in successfully
	 */
	public function login()
	{
		if ($this->validate()) {
			return Yii::$app->user->login($this->getUser(), $this->remember ? (3600 * 24 * 30) : 0);
		}

		return false;
	}

	/**
	 * Finds user by [[email]]
	 *
	 * @return User|null
	 */
	public function getUser()
	{
		if ($this->_user === false) {
			$model = User::findIdentityByEmail($this->email);

			if ($model and ($model->role == User::ROLE_USER or $model->role == User::ROLE_ADMINISTRATOR)) {
				$this->_user = $model;
			}
		}

		return $this->_user;
	}
}
