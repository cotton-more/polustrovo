<?php
// Routes
$app->get('/', function () {
    $response = new \Slim\Http\Response();

    $data = \App\Model\Screenshot::all();

    return $response->withJson($data);
});
$app->get('/image/{name}', function ($req, $resp, $args) {
    $resp = $this->get('glide.screenshot')->imageResponse($args['name']);

    return $resp;
});