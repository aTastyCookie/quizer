<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
		'urlManager' => [
			'class' => 'yii\web\UrlManager',
			// Disable index.php
			'showScriptName' => false,
			// Disable r= routes
			'enablePrettyUrl' => true,
			'rules' => array(
					'<controller:\w+>/<id:\d+>' => '<controller>/view',
					'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
					'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
					'user/registration/register' => 'user/registration/register',
					'/user/registration/resend' => '/user/registration/resend',
					'/user/registration/resend'=>'/user/registration/resend',
					'/user/registration/confirm'=>'/user/registration/confirm',
					'/user/security/login'=>'/user/security/login',
					'/user/security/logout'=>'/user/security/logout',
					'/user/recovery/request'=>'/user/recovery/request',
					'/user/recovery/reset'=>'/user/recovery/reset',
					'/user/settings/profile'=>'/user/settings/profile',
					'/user/settings/account'=>'/user/settings/account',
					'/user/settings/networks'=>'/user/settings/networks',
					'/user/profile/show'=>'/user/profile/show',
					'/user/admin/index'=>'/user/admin/index'
			),
        ],
		

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'ku_U7TAmam5Ilx8PmqQPvQ5PFTqUQkWG',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
       
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
       'mailer' => [
         'class' => 'katanyoo\mailgunmailer\Mailer',
          'domain' => 'quizer.pw',
          'key' => 'key-45efed5ca74f14fd6f80638bd70db170',
		  'fromAddress' => 'postmaster@quizer.pw',
          'tags' => ['yii'],
          'enableTracking' => false,
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
        'db' => require(__DIR__ . '/db.php'),
    ],

    'params' => $params,
    'modules' => [
	'user' => [
    	    'class' => 'dektrium\user\Module',
	],
    ]
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
