<?php

return [
    'migration_dirs' => [
        'migrations' => __DIR__ . '/migrations',
    ],
    'environments' => [
        'production' => [
            'adapter' => 'mysql',
            'host' => 'db',
            'port' => 3306, // optional
            'username' => 'admin',
            'password' => 'mysecretpassword',
            'db_name' => 'flixtrip',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci', // optional, if not set default collation for utf8mb4 is used
        ],
    ],
    'default_environment' => 'production',
    'log_table_name' => 'phoenix_log',
];
