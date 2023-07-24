<?php

$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'or2uhy_xAeww576EaRot-0AI1tOk8BqK',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => getenv('REDIS_HOST') ?: 'redis',
                'port' => getenv('REDIS_PORT') ?: 6379,
                'database' => (int)getenv('REDIS_DATABASE') ?: 0,
            ],
        ],
        'user' => [
            'identityClass' => 'hubbler\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [

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
        'rabbitmq' => [
            'class' => \app\service\RabbitMQ::class,
            'config' => [
                'RABBITMQ_HOST' => getenv('RABBITMQ_HOST') ?: 'localhost',
                'RABBITMQ_PORT' => getenv('RABBITMQ_PORT') ?: '5672',
                'RABBITMQ_USER' => getenv('RABBITMQ_USER') ?: 'admin',
                'RABBITMQ_PASS' => getenv('RABBITMQ_PASS') ?: 'pasword',
                'RABBITMQ_QUEUE_NAME' => getenv('RABBITMQ_QUEUE_NAME') ?: 'rates-queue',
                'RABBITMQ_EXCHANGE_NAME' => getenv('RABBITMQ_EXCHANGE_NAME') ?: 'rates-exchange',
                'RABBITMQ_ROUTING_NAME' => getenv('RABBITMQ_ROUTING_NAME') ?: 'crb',
            ]
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                'POST site' => 'site/index',
            ],
        ]
    ],
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
        // 'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
