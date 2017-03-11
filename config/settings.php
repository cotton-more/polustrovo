<?php

return [
    'url'            => getenv('URL'),
    'browshot_key'   => getenv('BROWSHOT_KEY'),
    'screenshot_dir' => realpath(__DIR__.'/../screenshots'),

    'root.dir' => realpath(__DIR__.'/..'),

    // Slim Settings
    'determineRouteBeforeAppMiddleware' => false,
    'displayErrorDetails' => true,

    'settings' => [
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
        ],
    ],
];