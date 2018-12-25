<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\imagine\Image;
use app\models\Client;
use app\models\search\ClientSearch;

/**
 * ClientController implements the CRUD actions for Client model.
 */
class ClientController extends Controller
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
	 * Lists all Client models.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new ClientSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		if ($searchModel->cpf) {
			if ($dataProvider->totalCount == 0) {
				return $this->redirect(['create', 'cpf' => $searchModel->cpf]);
			} else if ($dataProvider->totalCount == 1) {
				$model = Client::findIdentityByCpf($searchModel->cpf);

				if ($model) {
					return $this->redirect(['update', 'id' => $model->id]);
				}
			}
		}

		return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
	}

	/**
	 * Creates a new Client model.
	 * If creation is successful, the browser will be redirected to the 'index' page.
	 *
	 * @return mixed
	 */
	public function actionCreate($cpf = null)
	{
		$model = new Client();

		$model->scenario = 'create';

		if ($model->load(Yii::$app->request->post())) {
			if ($model->save() and $model->createPhotoBig() and $model->createPhotoThumb()) {
				if (!$model->checkin) {
					Yii::$app->session->setFlash('success', 'Cliente cadastrado com sucesso!');
				}

				return $this->redirect(['index']);
			}
		} else {
			$model->cpf = $cpf;
		}

		return $this->render('create', ['model' => $model]);
	}

	/**
	 * Updates an existing Client model.
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

		if ($model->load(Yii::$app->request->post())) {
			if ($model->save()) {
				if ($model->photo) {
					$model->createPhotoBig();
					$model->createPhotoThumb();
				}

				if (!$model->checkin) {
					Yii::$app->session->setFlash('success', 'Cliente atualizado com sucesso!');
				}

				return $this->redirect(['index']);
			}
		}

		return $this->render('update', ['model' => $model]);
	}

	/**
	 * Realiza checkin
	 *
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionCheckin($id)
	{
		$model = $this->findModel($id);

		if ($model->doCheckin()) {
			Yii::$app->session->setFlash('success', 'Checkin realizado com sucesso!');
		}

		return $this->redirect('index');
	}

	/**
	 * Download photo
	 *
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDownload($id)
	{
		$model = $this->findModel($id);

		return Yii::$app->response->sendFile($model::filePath() . '/' . $model->fileName());
	}

	/**
	 * Download photo big
	 *
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDownloadPhotoBig($id)
	{
		$model = $this->findModel($id);

		return Yii::$app->response->sendFile($model::filePath() . '/' . $model->fileNameBig());
	}

	/**
	 * Download photo thumb
	 *
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDownloadPhotoThumb($id)
	{
		$model = $this->findModel($id);

		return Yii::$app->response->sendFile($model::filePath() . '/' . $model->fileNameThumb());
	}

	/**
	 * Deletes an existing Client model.
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
	 * Finds the Client model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 * @return Client the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Client::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}
}
