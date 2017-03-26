<?php

return [
    'url'            => getenv('URL'),
    'browshot_key'   => getenv('BROWSHOT_KEY'),
    'screenshot_dir' => realpath(__DIR__.'/../screenshots'),
    'browshot' => [
        'api_key'     => getenv('BROWSHOT_KEY'),
        'instance_id' => getenv('BROWSHOT_INSTANCE'),
    ],

    'telegram' => [
        'token' => getenv('TELEGRAM_BOT_TOKEN'),
        'chat_id' => getenv('TELEGRAM_CHAT_ID'),
    ],

    'root.dir' => realpath(__DIR__.'/..'),
    'cache_dir' => realpath(__DIR__.'/../var/cache'),

    // Slim Settings
    'settings' => [
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => true,

        // Enable whoops
        'debug'         => true,

        'db' => [
            'driver' => 'sqlite',
            'host' => 'localhost',
            'database' => getenv('DB_DATABASE'),
            'username' => 'user',
            'password' => 'password',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],

        'view' => [
            'templates' => realpath(__DIR__.'/../templates'),
            'cache' => getenv('VIEW_CACHE') ?: false,
        ],

        'logger' => [
            'directory' => realpath(__DIR__.'/..'),
            // Your timezone
//            'timezone' => 'Asia/Jakarta',
            'level' => 'debug',
            'handlers' => [
                new \Monolog\Handler\StreamHandler('php://stdout'),
            ],
        ],
    ],
];