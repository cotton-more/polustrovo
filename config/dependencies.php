<?php

// DIC configuration

/** @var Slim\App $app */
$container = $app->getContainer();

$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig($c['settings']['view']['templates'], [
        'cache' => false,
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

    return $view;
};