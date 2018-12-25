<?php

use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $model app\models\User
 */

$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => 'Usuários', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_form', ['model' => $model]) ?>

</div>
