<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\MaskedInput;
use rmrevin\yii\fontawesome\FA;
use app\models\User;

/**
 * @var $this yii\web\View
 * @var $searchModel app\models\search\UserSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 */

$this->title = 'Usuários';

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-index">

	<h1>
		<?= FA::icon('cogs') ?>
		<?= Html::encode($this->title) ?>
		<?= Html::a('novo usuário', ['create'], ['class' => 'btn btn-sm btn-primary']) ?>
	</h1>

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
					'filter' => User::ROLES,
					'attribute' => 'role',
					'value' => function ($model) {
						return User::ROLES[$model->role];
					},
				],
				['class' => 'yii\grid\ActionColumn'],
			],
		]);
	?>

</div>
