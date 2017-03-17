<?php
// Routes
$app->get('/image/{screenshot_id}', function ($req, $resp, $args) {
    $resp = $this->get('glide.screenshot')->imageResponse($args['screenshot_id']);

    return $resp;
});

$app->get('/', 'index_controller:index')->setName('home');

$app->group('/screenshot', function () {
    $this->get('/current_week', 'screenshot_controller:currentWeekAction')->setName('screenshot_current_week');
});