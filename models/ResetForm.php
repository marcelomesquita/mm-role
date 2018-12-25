<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ResetForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class ResetForm extends Model
{
	public $password;
	public $password_confirmation;
	public $password_reset_token;

	private $_user = false;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			[['password', 'password_confirmation', 'password_reset_token'], 'required'],
			[['password', 'password_confirmation', 'password_reset_token'], 'string'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'password' => 'Senha',
			'password_confirmation' => 'Confirmar Senha',
		];
	}

	/**
	 * Reset password.
	 *
	 * @return bool whether the password is reseted
	 */
	public function reset()
	{
		if ($model = $this->getUser()) {
			$model->scenario = 'reset';

			$model->password = $this->password;
			$model->password_confirmation = $this->password_confirmation;

			$model->removePasswordResetToken();

			$model->save();

			Yii::$app->session->setFlash('success', 'Senha alterada com sucesso!');

			return true;
		} else {
			$this->addError('email', 'Token inválido.');
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
			$model = User::findIdentityByPasswordResetToken($this->password_reset_token);

			if ($model and ($model->role == User::ROLE_USER or $model->role == User::ROLE_ADMINISTRATOR)) {
				$this->_user = $model;
			}
		}

		return $this->_user;
	}
}
