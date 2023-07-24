<?php

$host = env('POSTGRES_HOST')/* ?: '192.168.224.4'*/;
$dbname = env('POSTGRES_DB')/* ?: 'hubbler'*/;
$username = env('POSTGRES_USER')/* ?: 'admin'*/;
$password = env('POSTGRES_PASSWORD')/* ?: 'secret'*/;

return [
    'class' => 'yii\db\Connection',
    'dsn' => "pgsql:host=$host;dbname=$dbname",
    'username' => $username,
    'password' => $password,
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
