<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');
$db_bank = require(__DIR__ . '/db_bank.php');

$config = [
	'id' => 'basic',
	'basePath' => dirname(__DIR__),
	'language' => 'fi',
	'extensions' => require(__DIR__ . '/../vendor/yiisoft/extensions.php'),
	'components' => [
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
		'mail' => [
			'class' => 'yii\swiftmailer\Mailer',
		],
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => true,
		],
		'i18n' => [
			'translations' => [
				'*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@app/messages',
					'sourceLanguage' => 'en',
				],
			],
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
	],
	'params' => $params,
	'modules' => [
		'gii' => [
			'class' => 'yii\gii\Module',
		]
	]
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['preload'][] = 'debug';
	$config['modules']['debug'] = 'yii\debug\Module';
}

return $config;
