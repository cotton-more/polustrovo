<?php
/**
 * Created by PhpStorm.
 * User: inikulin
 * Date: 09.03.17
 * Time: 19:55
 */

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
// Register middleware
//require __DIR__ . '/../config/middleware.php';

$kernel = new AppKernel();

$app = new \Slim\App($settings);

$kernel->register($app->getContainer());

require __DIR__ . '/../config/routes.php';

// Run app
$app->run();