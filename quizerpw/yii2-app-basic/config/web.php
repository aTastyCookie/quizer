<?php

define('UPLOAD_DIR', 'uploads');
define('ASSETS_DIR', 'assets');

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'components' => [
        'image' => [
            'class' => 'yii\image\ImageDriver',
            'driver' => 'GD',  //GD or Imagick
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'itemFile' => '@app/config/rbac/items.php',
            'assignmentFile' => '@app/config/rbac/assignments.php',
            'ruleFile' => '@app/config/rbac/rules.php',
            'defaultRoles' => ['admin', 'user']
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // Disable index.php
            'showScriptName' => false,
            // Disable r= routes
            'enablePrettyUrl' => true,
            'rules' => [

                //'<controller:\w+>/<id:\d+>' => '<controller>/view',
                //'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '' => 'site/index',
                '<controller:\w+>/' => '<controller>/index',
                'quest-<id:\d+>/' => 'quest/run',
                'quest-<url:\w+>/' => 'quest/run',






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
            ],
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
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en_US',
                    'fileMap' => [
                        'app' =>'app.php',
                    ]
                ]
            ]
        ],


        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'enableCsrfValidation' => false,
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
        'ym' => [
            'class' => 'grigorieff\ym\YMComponent',
            'client_id' => 'E43EE5FE9F53CA6A2C45847F6FC2472E0619A50F53EECC3574DD62CF9F89253B',
            'code' => '',
            'redirect_uri' => urlencode('https://quizer.pw/quest/showhint'),
            'client_secret' => '0548342FA33DECE04952532E2C3028DD47AF96DA199B99A65CB5C28E5938DA0EFFBA9AECFED64A810F746325E4B17DBF20E052D0E5896B151E545E55DE811512'
        ],
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