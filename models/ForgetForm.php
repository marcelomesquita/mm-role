<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * ForgetForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class ForgetForm extends Model
{
	public $email;

	private $_user = false;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			[['email'], 'required'],
			[['email'], 'email'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'email' => 'E-mail',
		];
	}

	/**
	 * Forget password.
	 *
	 * @return bool whether the password is reseted
	 */
	public function forget()
	{
		if ($model = $this->getUser()) {
			$model->scenario = 'forget';

			$model->generatePasswordResetToken();

			$model->save();

			// enviar email
			Yii::$app->mailer
				->compose('layouts/html', ['content' => $this->getMailMessage($model)])
				->setSubject(Yii::$app->name . ': Recuperação de Senha')
				->setFrom(Yii::$app->params['adminEmail'])
				->setTo($model->email)
				->send();

			Yii::$app->session->setFlash('success', 'Um e-mail foi enviado com o link para recuperação de senha!');

			return true;
		} else {
			$this->addError('email', 'E-mail não encontrado.');
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

	/**
	 * Montar mensagem de e-mail
	 *
	 * @return string
	 */
	private function getMailMessage($model)
	{
		$message = Html::tag('p', "Prezado {$model->name},");
		$message .= Html::tag('p', 'Uma solicitação para recuperação da senha foi realizada.');
		$message .= Html::tag('p', 'Para prosseguir com a alteração, clique no link abaixo:');
		$message .= Html::tag('p', Html::a('alterar senha', Url::to(['site/reset', 'password_reset_token' => $model->password_reset_token], true)));
		$message .= Html::tag('p', 'O link é válido apenas por um dia.');

		return $message;
	}
}
