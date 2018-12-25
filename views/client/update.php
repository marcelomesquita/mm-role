<?php

use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $model app\models\Client
 */

$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="client-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php if ($model->lastCheckin) : ?>
		<p class="text-muted">
			<small><em>
				último checkin em <?= Yii::$app->formatter->asDatetime($model->lastCheckin->checkin) ?>
				<?= Html::a(Html::tag('i', '', ['class' => 'glyphicon glyphicon-triangle-bottom']), '#', ['title' => 'ver todos os checkins', 'onClick' => '$("#checkins").toggle("fast")']) ?>
			</em></small>
		</p>

		<div id="checkins" style="height: 100px; margin: 10px; overflow: scroll; display: none;">
			<?php foreach ($model->checkins as $checkin) : ?>
				<p class="text-muted"><small><em> - <?= Yii::$app->formatter->asDatetime($checkin->checkin) ?></em></small></p>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?= $this->render('_form', ['model' => $model]) ?>

</div>
