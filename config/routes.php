<?php
// Routes
$app->get('/image/{screenshot_id}', function ($req, $resp, $args) {
    $resp = $this->get('glide.screenshot')->imageResponse($args['screenshot_id']);

    return $resp;
});

$app->get('/', function ($req, $resp) {
    $image = $this->get('screenshot_service')->getLatestImage();

    return $this->view->render($resp, 'index.twig', [
        'image' => $image,
    ]);
});