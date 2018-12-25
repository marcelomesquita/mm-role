<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use kartik\datecontrol\DateControl;

/**
 * @var $this yii\web\View
 * @var $model app\models\search\ClientSearch
 * @var $form yii\widgets\ActiveForm
 */

$this->registerJs('$("#clientsearch-checkin_day").on("change", function() { $(this).submit(); })');

?>
<div class="user-search">

	<?php $form = ActiveForm::begin(['action' => ['index'], 'method' => 'get']); ?>

	<div class="row">
		<div class="col-sm-6">
			<?= Html::label($model->getAttributeLabel('cpf')) ?>
			<div class="form-group">
				<div class="input-group">
					<?=
						MaskedInput::widget([
							'model' => $model,
							'attribute' => 'cpf',
							'mask' => '999.999.999-99',
							'options' => [
								'autofocus' => 'autofocus',
								'class' => 'form-control',
							],
							'clientOptions' => [
								'removeMaskOnSubmit' => true,
							],
						]);
					?>
					<span class="input-group-btn">
						<?= Html::submitButton(Html::tag('i', '', ['class' => 'glyphicon glyphicon-search']), ['class' => 'btn btn-default']) ?>
					</span>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<?= Html::label($model->getAttributeLabel('checkin_day')) ?>
			<?=
				DateControl::widget([
					'model' => $model,
					'attribute' => 'checkin_day',
					'type' => DateControl::FORMAT_DATE,
					'widgetOptions' => [
						'pluginOptions' => [
							'autoclose' => true
						]
					]
				]);
			?>
		</div>
	</div>

	<?php ActiveForm::end(); ?>

</div>
