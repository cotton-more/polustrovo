#!/usr/bin/env php
<?php

if (!$loader = include __DIR__ . '/../vendor/autoload.php') {
    die('You must set up the project dependencies.');
}

$dotenv = new \Dotenv\Dotenv(__DIR__.DIRECTORY_SEPARATOR.'..');
$dotenv->load();

$app = new \Cilex\Application('Polustrovo Screenshot');

$app->register(new Cli\Provider\ConfigProvider());
$app->register(new Cli\Provider\ScreenshotServiceProvider());

$app['root.dir'] = realpath(__DIR__.'/..');

$app->command(new Cli\Command\TakeScreenshot());

$app->run();
