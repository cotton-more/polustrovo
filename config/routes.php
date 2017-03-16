<?php
// Routes
$app->get('/image/{screenshot_id}', function ($req, $resp, $args) {
    $resp = $this->get('glide.screenshot')->imageResponse($args['screenshot_id']);

    return $resp;
});

$app->get('/', 'index_controller:index');