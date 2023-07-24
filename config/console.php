<?php

use app\service\RabbitMQ;

$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'components' => [
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => env('REDIS_HOST') ?: 'redis',
                'port' => env('REDIS_PORT') ?: 6379,
                'database' => (int)env('REDIS_DATABASE') ?: 0,
            ],
        ],
        'db' => $db,
        'rabbitmq' => [
            'class' => RabbitMQ::class,
            'config' => [
                'RABBITMQ_HOST' => env('RABBITMQ_HOST')/* ?: 'rabbitmq'*/,
                'RABBITMQ_PORT' => env('RABBITMQ_PORT')/* ?: '5672'*/,
                'RABBITMQ_USER' => env('RABBITMQ_USER')/* ?: 'admin'*/,
                'RABBITMQ_PASS' => env('RABBITMQ_PASS')/* ?: 'pasword'*/,
                'RABBITMQ_QUEUE_NAME' => env('RABBITMQ_QUEUE_NAME')/* ?: 'rates-queue'*/,
                'RABBITMQ_EXCHANGE_NAME' => env('RABBITMQ_EXCHANGE_NAME')/* ?: 'rates-exchange'*/,
                'RABBITMQ_ROUTING_NAME' => env('RABBITMQ_ROUTING_NAME')/* ?: 'crb'*/,
            ]
        ],
    ],
    'controllerMap' => [
        'queue' => [
            'class' => 'UrbanIndo\Yii2\Queue\Console\Controller',
            //'sleepTimeout' => 1
        ],
    ],

];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
