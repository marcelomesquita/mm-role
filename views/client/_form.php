<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use kartik\datecontrol\DateControl;
use kartik\widgets\SwitchInput;
use app\models\Client;

/**
 * @var $this yii\web\View
 * @var $model app\models\User
 * @var $form yii\widgets\ActiveForm
 */

$this->registerJs('
function handleFiles()
{
	var dataurl = null;
	var filesToUpload = document.getElementById("file").files;
	var file = filesToUpload[0];

	var img = document.createElement("img");
	var reader = new FileReader();

	reader.onload = function(e)
	{
		img.src = e.target.result;

		img.onload = function () {
			var canvas = document.createElement("canvas");
			var ctx = canvas.getContext("2d");

			ctx.drawImage(img, 0, 0);

			var MAX_WIDTH = 800;
			var MAX_HEIGHT = 800;
			var width = img.width;
			var height = img.height;

			if (width > height) {
				if (width > MAX_WIDTH) {
					height *= MAX_WIDTH / width;
					width = MAX_WIDTH;
				}
			} else {
				if (height > MAX_HEIGHT) {
					width *= MAX_HEIGHT / height;
					height = MAX_HEIGHT;
				}
			}

			// transform context before drawing image
			if (width > height) {
				canvas.width = height;
				canvas.height = width;

				ctx.transform(0, 1, -1, 0, height , 0);
			} else {
				canvas.width = width;
				canvas.height = height;
			}

			ctx.drawImage(img, 0, 0, width, height);

			var dataurl = canvas.toDataURL("image/jpeg");

			document.getElementById("photo").value = dataurl;
			document.getElementById("preview").src = dataurl;
		}
	}

	// Load files into file reader
	reader.readAsDataURL(file);

	document.getElementById("file").value = null;
}
', yii\web\View::POS_HEAD);

?>
<div class="user-form">

	<?php $form = ActiveForm::begin(); ?>

	<div class="row">
		<div class="col-sm-8">
			<div class="row">
				<div class="col-sm-12">
					<?=
						$form->field($model, 'foreign')->widget(SwitchInput::classname(), [
							'pluginEvents' => [
								'switchChange.bootstrapSwitch' => 'function () { $("#foreign, #native").toggle("fast"); }',
							]
						])
					?>
				</div>
			</div>

			<div id="foreign" <?= $model->foreign ? 'style="display:block;"' : 'style="display:none;"' ?>>
				<div class="row">
					<div class="col-sm-12">
						<?= $form->field($model, 'foreign_register_type')->dropDownList(Client::FOREIGN_REGISTER_TYPES, ['prompt' => 'Escolha um tipo de documento']) ?>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<?= $form->field($model, 'foreign_register')->textInput() ?>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<?= $form->field($model, 'name')->textInput(['autofocus' => 'autofocus', 'maxlength' => true]) ?>
				</div>
			</div>

			<div id="native" <?= !$model->foreign ? 'style="display:block;"' : 'style="display:none;"' ?>>
				<div class="row">
					<div class="col-sm-12">
						<?=
							$form->field($model, 'cpf')->widget(MaskedInput::className(), [
								'mask' => '999.999.999-99',
								'clientOptions' => [
									'removeMaskOnSubmit' => true,
								],
							])
						?>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-6">
					<?= Html::label($model->getAttributeLabel('sex')) ?>
					<?=
						$form->field($model, 'sex')->radioList(
							Client::SEX,
							[
								'class' => 'btn-group',
								'data' => ['toggle' => 'buttons'],
								'item' => function($index, $label, $name, $checked, $value) {
									$active = $checked ? 'active' : '';
									$checked = $checked ? 'checked="checked"' : '';

									return "<label class='btn btn-default {$active}'><input type='radio' name='{$name}' value='{$value}' {$checked}> {$label}</label>";
								}
							]
						)->label(false);
					?>
				</div>
				<div class="col-sm-6">
					<?=
						$form->field($model, 'birthday')->widget(DateControl::classname(), [
							'displayFormat' => 'dd/MM/yyyy',
							'autoWidget' => false,
							'widgetClass' => 'yii\widgets\MaskedInput',
							'widgetOptions' => [
								'mask' => '99/99/9999',
								'options' => [
									'class' => 'form-control',
								],
							],
						]);
					?>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<?=
						$form->field($model, 'phone')->widget(MaskedInput::className(), [
							'type' => 'tel',
							'mask' => '(99) 99999-9999',
						])
					?>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<?= $form->field($model, 'email')->input('email') ?>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<?= $form->field($model, 'file')->fileInput(['id' => 'file', 'onChange' => 'handleFiles()']) ?>
					<?= $form->field($model, 'photo')->hiddenInput(['id' => 'photo'])->label(false) ?>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<?=
						$form->field($model, 'cop')->widget(SwitchInput::classname(), [
							'pluginEvents' => [
								'switchChange.bootstrapSwitch' => 'function () { $("#cop").toggle("fast"); }',
							]
						])
					?>
				</div>
			</div>

			<div id="cop" <?= $model->cop ? 'style="display:block;"' : 'style="display:none;"' ?>>
				<div class="row">
					<div class="col-sm-12">
						<?= $form->field($model, 'cop_institution')->dropDownList(Client::COP_INSTITUTIONS, ['prompt' => 'Escolha uma instituição']) ?>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<?= $form->field($model, 'cop_register')->textInput() ?>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<?= $form->field($model, 'cop_weapon')->textInput(['maxlength' => true]) ?>
					</div>
				</div>
			</div>

			<div class="form-group">
				<?= Html::submitButton('Checkin', ['class' => 'btn btn-success', 'name' => 'Client[checkin]', 'value' => 1]) ?>
				<?= Html::submitButton('Salvar', ['class' => 'btn btn-default']) ?>
			</div>
		</div>

		<div class="col-sm-4">
			<?php if ($model->hasPhotoBig()) : ?>
				<?= Html::img(Url::toRoute(['download-photo-big', 'id' => $model->id]), ['id' => 'preview', 'class' => 'img-rounded', 'width' => '100%']) ?>
			<?php else : ?>
				<?= Html::img($model->photo, ['id' => 'preview', 'class' => 'img-rounded', 'width' => '100%']) ?>
			<?php endif; ?>
		</div>
	</div>

	<?php ActiveForm::end(); ?>

</div>
