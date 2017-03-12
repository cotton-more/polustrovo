<?php

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';
session_start();
// Instantiate the app
$settings = require __DIR__ . '/../config/settings.php';

$app = new \Slim\App($settings);

// Register middleware
// require __DIR__ . '/../config/middleware.php';

/** @var \Slim\Container $container */
$container = $app->getContainer();

$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware($app));

$kernel = new AppKernel($container);

require __DIR__ . '/../config/routes.php';

// Run app
$app->run();