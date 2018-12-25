<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\User;

/**
 * @var $this yii\web\View
 * @var $model app\models\User
 * @var $form yii\widgets\ActiveForm
 */

$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => 'Usuários', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-profile">

	<h1><?= Html::encode($this->title) ?></h1>

	<div class="user-form">

		<?php $form = ActiveForm::begin(); ?>

		<div class="row">
			<div class="col-sm-12">
				<?= $form->field($model, 'email')->textInput(['autofocus' => 'autofocus', 'maxlength' => true]) ?>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<?= $form->field($model, 'password')->passwordInput() ?>
			</div>
			<div class="col-sm-6">
				<?= $form->field($model, 'password_confirmation')->passwordInput() ?>
			</div>
		</div>

		<div class="form-group">
			<?= Html::submitButton('Salvar', ['class' => 'btn btn-primary']) ?>
		</div>

		<?php ActiveForm::end(); ?>

	</div>

</div>
