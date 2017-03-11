<?php
// Routes
$app->get('/[{name}]', function () {

    $model = new \App\Model\Screenshot();
    dump($model->save()); die;
});