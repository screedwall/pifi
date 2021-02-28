<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=127.0.0.1;port=5432;dbname=yii2',
    'username' => 'postgres',
    'password' => 'QBZ95rf',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    'enableSchemaCache' => false,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',
];
