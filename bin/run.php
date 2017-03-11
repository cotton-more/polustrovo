#!/usr/bin/env php
<?php

if (!$loader = include __DIR__ . '/../vendor/autoload.php') {
    die('You must set up the project dependencies.');
}

$settings = require __DIR__ . '/../config/settings.php';

$kernel = new AppKernel();

$app = new \Cilex\Application('Polustrovo Screenshot', '0.1', $settings);

foreach ($kernel->providers() as $provider) {
    $app->register($provider);
}

$app->command(new Cilex\Command\TakeScreenshot());

$app->run();
