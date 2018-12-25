<?php

require __DIR__ . '/container.php';

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/web_db.php';

$config = [
	'id' => 'basic',
	'name' => 'Rolê',
	'language' => 'pt-BR',
	'timezone' => 'America/Sao_Paulo',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log'],
	'defaultRoute' => 'client/index',
	'aliases' => [
		'@bower' => '@vendor/bower-asset',
		'@npm'   => '@vendor/npm-asset',
	],
	'components' => [
		'request' => [
			'cookieValidationKey' => '0',
		],
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		'user' => [
			'identityClass' => 'app\models\User',
			'enableAutoLogin' => true,
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'mailer' => [
			'class' => 'yii\swiftmailer\Mailer',
			'useFileTransport' => false,
		],
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'db' => $db,
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [
				'<controller>' => '<controller>/index',
				'<controller>/<id:\d+>' => '<controller>/view'
			],
		],
		'formatter' => [
			'class' => 'yii\i18n\Formatter',
			'dateFormat' => 'php:d/m/Y',
			'datetimeFormat' => 'php:d/m/Y H:i:s',
			'timeFormat' => 'php:H:i:s',
		],
	],
	'modules' => [
		'datecontrol' => [
			'class' => 'kartik\datecontrol\Module',
			'displaySettings' => [
				'date' => 'dd/MM/yyyy',
				'time' => 'HH:mm:ss',
				'datetime' => 'dd/MM/yyyy HH:mm:ss',
			],
			'saveSettings' => [
				'date' => 'yyyy-MM-dd',
				'time' => 'HH:mm:ss',
				'datetime' => 'yyyy-MM-dd HH:mm:ss',
			],
			'autoWidget' => true,
			'autoWidgetSettings' => [
				'date' => ['type' => 3, 'pluginOptions' => ['autoclose' => true]],
				'datetime' => [],
				'time' => [],
			],
		],
	],
	'params' => $params,
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][] = 'debug';
	$config['modules']['debug'] = [
		'class' => 'yii\debug\Module',
		// uncomment the following to add your IP if you are not connecting from localhost.
		//'allowedIPs' => ['127.0.0.1', '::1'],
	];

	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
		// uncomment the following to add your IP if you are not connecting from localhost.
		//'allowedIPs' => ['127.0.0.1', '::1'],
	];
}

return $config;
