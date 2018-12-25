<?php

/**
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model app\models\LoginForm
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Esqueci minha senha';

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-login">
	<h1><?= Html::encode($this->title) ?></h1>

	<?php $form = ActiveForm::begin(); ?>

		<div class="row">
			<div class="col-sm-12">
				<?= $form->field($model, 'email')->input('email', ['autofocus' => true]) ?>
			</div>
		</div>

		<div class="form-group">
			<?= Html::submitButton('Recuperar senha', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
		</div>

	<?php ActiveForm::end(); ?>
</div>
