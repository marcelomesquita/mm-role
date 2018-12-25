<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\models\LoginForm;
use app\models\ForgetForm;
use app\models\ResetForm;

class SiteController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'except' => ['login', 'forget', 'reset'],
				'rules' => [
					[
						'actions' => ['index', 'flush', 'logout'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}

	/**
	 * Displays homepage.
	 *
	 * @return string
	 */
	public function actionIndex()
	{
		return $this->render('index');
	}

	/**
	 * @return mixed
	 */
	public function actionFlush()
	{
		Yii::$app->cache->flush();

		return $this->goHome();
	}

	/**
	 * Login action.
	 *
	 * @return Response|string
	 */
	public function actionLogin()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();

		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->goBack();
		}

		$model->password = '';

		return $this->render('login', ['model' => $model]);
	}

	/**
	 * Logout action.
	 *
	 * @return Response
	 */
	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->goHome();
	}

	/**
	 * Forget password action.
	 *
	 * @return Response
	 */
	public function actionForget()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new ForgetForm();

		if ($model->load(Yii::$app->request->post()) && $model->forget()) {
			return $this->goBack();
		}

		return $this->render('forget', ['model' => $model]);
	}

	/**
	 * Reset password action.
	 *
	 * @return Response
	 */
	public function actionReset($password_reset_token = null)
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new ResetForm();

		if (!User::validatePasswordResetToken($password_reset_token)) {
			$model->addError('password_reset_token', 'Token inválido!');
		}

		if ($model->load(Yii::$app->request->post()) && $model->reset()) {
			return $this->goBack();
		}

		$model->password = '';
		$model->password_confirmation = '';
		$model->password_reset_token = $password_reset_token;

		return $this->render('reset', ['model' => $model]);
	}
}
