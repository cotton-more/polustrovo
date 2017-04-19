<?php
// Routes
use Psr\Http\Message\ServerRequestInterface;

$app->get('/image/{screenshot_id}', function (ServerRequestInterface $req, $resp, $args) {
    $resp = $this->get('glide.screenshot')->imageResponse($args['screenshot_id'], $req->getQueryParams());

    return $resp;
});

$app->get('/', 'index_controller:index')->setName('home');

$app->group('/screenshot', function () {
    $this->get('/current_week', 'screenshot_controller:currentWeekAction')
        ->setName('screenshot_current_week');

    $this->get('/calendar', 'screenshot_controller:calendarAction')
        ->setName('screenshot_calendar');

    $this->get('/calendar/{date}', 'screenshot_controller:dateAction')
        ->setName('screenshot_date');
});

$app->get('/telegram', function () {
    $bot = $this->get('telegram_bot.bot_api');

    dump($bot);
});

$app->get('/pushbullet', function () {
    /** @var \Pushbullet\Pushbullet $pb */
    $pb = $this->get('pushbullet.notifier');

    dump($pb);
});