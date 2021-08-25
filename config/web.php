<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'language' => 'ru',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            //'baseUrl' => '',
            'cookieValidationKey' => '5gNTJRBqUBpu2yux6zL4kR_BdeF5fhlW',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => ['<action>' => 'site/<action>'],
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
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.

//            'messageConfig' => [
//
//            'from' => ['usluga@cek.dp.ua' => 'usluga'],
//
//             ],

            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => '192.168.55.1',
                'username' => 'usluga@cek.dp.ua',
                'password' => 'kKvdRaCT4Q',
                'port' => '587',
                'encryption' => 'tls',

                'streamOptions' => [
                    'ssl' => [
                        'allow_self_signed' => true,
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ],

            ],

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
        'db' => require(__DIR__ . '/db.php'),
        'db_1' => require(__DIR__ . '/db_1.php'),
        'db_2' => require(__DIR__ . '/db_2.php'),
        'db_3' => require(__DIR__ . '/db_3.php'),
        'db_4' => require(__DIR__ . '/db_4.php'),
        'db_5' => require(__DIR__ . '/db_5.php'),
        'db_6' => require(__DIR__ . '/db_6.php'),
        'db_7' => require(__DIR__ . '/db_7.php'),
        'db_8' => require(__DIR__ . '/db_8.php'),
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => '',
        ]
    ],
   // 'pdf' => [
        //'class' => Pdf::classname(),
        //'format' => Pdf::FORMAT_A4,
        //'orientation' => Pdf::ORIENT_PORTRAIT,
        //'destination' => Pdf::DEST_BROWSER,
        // refer settings section for all configuration options
   // ],
    'modules' => [

        'images' => [
            'class' => 'circulon\images\Module',
            // be sure, that permissions ok
            // if you cant avoid permission errors you have to create "images" folder in web root manually and set 777 		permissions
            'imagesStorePath' => 'store', //path to origin images
            'imagesCachePath' => 'cache', //path to resized copies
            'graphicsLibrary' => 'GD', //but really its better to use 'Imagick'
            //'placeholderPath' => '@webroot/store/placeholder.png', // if you want to get placeholder when image not 		exists, string will be processed by Yii::getAlias
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
