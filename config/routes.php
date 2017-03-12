<?php
// Routes
$app->get('/[{name}]', function () {

    $model = new \App\Model\Screenshot();

    try {
        $all = $model::all();
    } catch (\Exception $ex) {
        dump($ex);
    }

    dump($all);
});