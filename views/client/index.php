<?php

use yii\db\Query;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\MaskedInput;
use yii\bootstrap\Modal;
use rmrevin\yii\fontawesome\FA;
use app\models\Client;
use app\models\User;
use app\models\Checkin;

/**
 * @var $this yii\web\View
 * @var $searchModel app\models\search\ClientSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 */

$this->title = 'Clientes';

$this->params['breadcrumbs'][] = $this->title;

Modal::begin(['id' => 'modal']);
Modal::end();

?>
<div class="client-index">

	<h1>
		<?= FA::icon('users') ?>
		<?= Html::encode($this->title) ?>
		<?= Html::a('novo cliente', ['create'], ['class' => 'btn btn-sm btn-primary']) ?>
	</h1>

	<?php if (Yii::$app->user->identity->role == User::ROLE_ADMINISTRATOR) : ?>
		<div class="row">
			<div class="col-xs-4 col-sm-2">
				<div class="panel panel-default">
					<div class="panel-body">
						<p><strong><?= FA::icon('file') ?> Clientes</strong></p>
						<h4><?= Html::a(Client::find()->count(), ['/client']) ?></h4>
					</div>
				</div>
			</div>
			<div class="col-xs-4 col-sm-2">
				<div class="panel panel-default">
					<div class="panel-body">
						<p><strong><?= FA::icon('check') ?> Checkins</strong></p>
						<h4><?= Html::a(Checkin::find()->count(), ['/client']) ?></h4>
					</div>
				</div>
			</div>
			<div class="col-xs-4 col-sm-2">
				<div class="panel panel-default">
					<div class="panel-body">
						<p><strong><?= FA::icon('male') ?> Homens</strong></p>
						<h4><?= Html::a(Client::find()->where(['sex' => 1])->count(), ['/client', 'ClientSearch[sex]' => Client::SEX_MALE]) ?></h4>
					</div>
				</div>
			</div>
			<div class="col-xs-4 col-sm-2">
				<div class="panel panel-default">
					<div class="panel-body">
						<p><strong><?= FA::icon('female') ?> Mulheres</strong></p>
						<h4><?= Html::a(Client::find()->where(['sex' => 2])->count(), ['/client', 'ClientSearch[sex]' => Client::SEX_FEMALE]) ?></h4>
					</div>
				</div>
			</div>
			<div class="col-xs-4 col-sm-2">
				<div class="panel panel-default">
					<div class="panel-body">
						<p><strong><?= FA::icon('calendar') ?> Idade Média</strong></p>
						<h4><?= Html::a((int) (new Query)->select('year(current_date) - round(avg(year(birthday))) idade_media')->from('client')->one()['idade_media'], '#') ?></h4>
					</div>
				</div>
			</div>
			<div class="col-xs-4 col-sm-2">
				<div class="panel panel-default">
					<div class="panel-body">
						<p><strong><?= FA::icon('cogs') ?> Usuários</strong></p>
						<h4><?= Html::a(User::find()->count(), ['/user']) ?></h4>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?= $this->render('_search', ['model' => $searchModel]); ?>

	<?=
		GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel' => $searchModel,
			'columns' => [
				'name',
				[
					'filterOptions' => ['class' => 'hidden-xs'],
					'headerOptions' => ['class' => 'hidden-xs'],
					'contentOptions' => ['class' => 'hidden-xs'],
					'attribute' => 'email',
					'format' => 'email',
				],
				[
					'filterOptions' => ['class' => 'hidden-xs'],
					'headerOptions' => ['class' => 'hidden-xs'],
					'contentOptions' => ['class' => 'hidden-xs'],
					'attribute' => 'cpf',
				],
				[
					'attribute' => 'lastCheckin',
					'value' => function($model) {
						return $model->lastCheckin ? Yii::$app->formatter->asDateTime($model->lastCheckin->checkin . Yii::$app->getTimeZone()) : 'nenhum';
					}
				],
				[
					'filterOptions' => ['class' => 'hidden-xs'],
					'headerOptions' => ['class' => 'hidden-xs text-center'],
					'contentOptions' => ['class' => 'hidden-xs text-center', 'width' => '100px'],
					'label' => $searchModel->getAttributeLabel('photo'),
					'attribute' => 'photo',
					'format' => 'raw',
					'value' => function($model) {
						return Html::a(Html::img(Url::toRoute(['download-photo-thumb', 'id' => $model->id]), ['class' => 'img-rounded']), '#', [
							'onClick' => "$('#modal').modal('show').find('.modal-body').html('" . Html::img(Url::toRoute(['download-photo-big', 'id' => $model->id]), ['class' => 'img-rounded', 'width' => '100%']) . "');"
						]);
					}
				],
				[
					'class' => 'yii\grid\ActionColumn',
					'template' => '<div class="btn-group" role="group" aria-label="Ações">{checkin} {update} {delete}</div>',
					'buttons' => [
						'checkin' => function ($url, $model) {
							return Html::a(Html::tag('i', '', ['class' => 'glyphicon glyphicon-ok']), ['checkin', 'id' => $model->id], ['class' => 'btn btn-success btn-checkin', 'title' => 'Checkin']);
						},
						'update' => function ($url) {
							return Html::a(Html::tag('i', '', ['class' => 'glyphicon glyphicon-pencil']), $url, ['class' => 'btn btn-default', 'title' => 'Editar']);
						},
						'delete' => function ($url) {
							return Html::a(Html::tag('i', '', ['class' => 'glyphicon glyphicon-trash']), $url, ['class' => 'btn btn-danger', 'title' => 'Excluir', 'data' => ['confirm' => 'Deseja realmente excluir esse item?', 'method' => 'post']]);
						}
					],
				],
			],
		]);
	?>

</div>
