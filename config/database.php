<?php

return [
    'fetch' => PDO::FETCH_CLASS,
    'default' => env('DB_CONNECTION', 'mysql'),
    'migrations' => 'migrations',
    'redis' => [
        'client' => 'phpredis',
        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
            'persistent' => true
        ],
    ],
    'connections' => [
        'testing' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ],
        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => env('DB_DATABASE', base_path('database/database.sqlite')),
            'prefix'   => env('DB_PREFIX', ''),
        ],
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', 'localhost'),
            'port'      => env('DB_PORT', 3306),
            'database'  => env('DB_DATABASE', 'lumen'),
            'username'  => env('DB_USERNAME', 'root'),
            'password'  => env('DB_PASSWORD', '654123'),
            'charset'   => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix'    => env('DB_PREFIX', ''),
            'timezone'  => env('DB_TIMEZONE', '+00:00'),
            'strict'    => env('DB_STRICT_MODE', false),
            'engine' => env('DB_ENGINE', 'InnoDB'),
        ],
        'pgsql' => [
            'driver'   => 'pgsql',
            'host'     => env('DB_HOST', 'localhost'),
            'port'     => env('DB_PORT', 5432),
            'database' => env('DB_DATABASE', 'lumen'),
            'username' => env('DB_USERNAME', 'lumen'),
            'password' => env('DB_PASSWORD', 'lumen'),
            'charset'  => env('DB_CHARSET', 'utf8'),
            'prefix'   => env('DB_PREFIX', ''),
            'schema'   => env('DB_SCHEMA', 'public'),
        ],
        'sqlsrv' => [
            'driver'   => 'sqlsrv',
            'host'     => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'lumen'),
            'username' => env('DB_USERNAME', 'lumen'),
            'password' => env('DB_PASSWORD', 'lumen'),
            'charset'  => env('DB_CHARSET', 'utf8'),
            'prefix'   => env('DB_PREFIX', ''),
        ],
    ],
];
