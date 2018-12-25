<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\models\search\UserSearch;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all User models.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new UserSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
	}

	/**
	 * Creates a new User model.
	 * If creation is successful, the browser will be redirected to the 'index' page.
	 *
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new User();

		$model->scenario = 'create';

		if ($model->load(Yii::$app->request->post()) and $model->save()) {
			Yii::$app->session->setFlash('success', 'Usuário cadastrado com sucesso!');

			return $this->redirect(['index']);
		}

		return $this->render('create', ['model' => $model]);
	}

	/**
	 * Updates an existing User model.
	 * If update is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		$model->scenario = 'update';

		if ($model->load(Yii::$app->request->post()) and $model->save()) {
			Yii::$app->session->setFlash('success', 'Usuário atualizado com sucesso!');

			return $this->redirect(['index']);
		}

		return $this->render('update', ['model' => $model]);
	}

	/**
	 * Updates own User model.
	 *
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionProfile()
	{
		$model = $this->findModel(Yii::$app->user->identity->id);

		$model->scenario = 'profile';

		if ($model->load(Yii::$app->request->post()) and $model->save()) {
			Yii::$app->session->setFlash('success', 'Usuário atualizado com sucesso!');

			return $this->redirect(['/']);
		}

		return $this->render('profile', ['model' => $model]);
	}

	/**
	 * Deletes an existing User model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);

		if ($model->delete()) {
			Yii::$app->session->setFlash('success', 'Usuário excluído!');
		} else {
			Yii::$app->session->setFlash('error', 'Falha ao excluir usuário!');
		}

		return $this->redirect(['index']);
	}

	/**
	 * Finds the User model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 * @return User the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = User::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}
}
