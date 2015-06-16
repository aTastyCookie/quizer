<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
		 'image' => array(
                'class' => 'yii\image\ImageDriver',
                'driver' => 'GD',  //GD or Imagick
                ),
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
		'authClientCollection' => [
				'class'   => \yii\authclient\Collection::className(),
				'clients' => [
					'facebook' => [
						'class'        => 'dektrium\user\clients\Facebook',
						'clientId'     => '854797341254242',
						'clientSecret' => '27d3441ec4de787bcaf0c2ab577b803c',
					],
					'vkontakte' => [
						'class'        => 'dektrium\user\clients\VKontakte',
						'clientId'     => '4865600',
						'clientSecret' => '6FuSKlnQvCdejonvKlgM',
					],
					'twitter' => [
						'class'          => 'dektrium\user\clients\Twitter',
						'consumerKey'    => 'lMwrTQD2joDP77acOxBAyrHC1',
						'consumerSecret' => 'f6dlOubQExmaJOAMKoU1jQ15EjnvPW8pFdoGJgZPRyiILfoxx0',
					],
					'google' => [
						'class'        => 'dektrium\user\clients\Google',
						'clientId'     => '409611339644-8q7abcn8g66b0fcrvkfr18e0chbibgtt.apps.googleusercontent.com',
						'clientSecret' => 'JJ4J2yjrFLjdbMxbp0oXdKbt',
					],
					'github' => [
						'class'        => 'dektrium\user\clients\GitHub',
						'clientId'     => 'ec6505862adfd8c7be12',
						'clientSecret' => '43203d465035b2767af1c53ce7b30bc85af64bb7',
					],
					'yandex' => [
						'class'        => 'dektrium\user\clients\Yandex',
						'clientId'     => '22cc1e1e4e6543e3b6b6e371b9a86795',
						'clientSecret' => 'a85b3081b5bd44e9accbcaa645934345'
					],
				],
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
