<?php

use yii\helpers\Html;

Yii::$container->set('yii\grid\GridView', [
	'tableOptions' => [
		'class' => 'table table-condensed table-striped',
	],
]);

Yii::$container->set('yii\grid\ActionColumn', [
	'headerOptions' => ['class' => 'text-center'],
	'contentOptions' => ['class' => 'text-center'],
	'template' => '<div class="btn-group" role="group" aria-label="Açoes">{update} {delete}</div>',
	'buttons' => [
		'view' => function ($url) {
			return Html::a(Html::tag('i', '', ['class' => 'glyphicon glyphicon-eye-open']), $url, ['class' => 'btn btn-default', 'title' => 'Exibir']);
		},
		'update' => function ($url) {
			return Html::a(Html::tag('i', '', ['class' => 'glyphicon glyphicon-pencil']), $url, ['class' => 'btn btn-default', 'title' => 'Editar']);
		},
		'delete' => function ($url) {
			return Html::a(Html::tag('i', '', ['class' => 'glyphicon glyphicon-trash']), $url, ['class' => 'btn btn-danger', 'title' => 'Excluir', 'data' => ['confirm' => 'Deseja realmente excluir esse item?', 'method' => 'post']]);
		}
	],
]);
