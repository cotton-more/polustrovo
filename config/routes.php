<?php
// Routes
$app->get('/', function () {
    $response = new \Slim\Http\Response();

    $data = \App\Model\Screenshot::all();

    return $response->withJson($data);
});