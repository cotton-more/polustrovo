<?php

namespace App\Service\Notifier;

use App\Entity\ScreenshotBroadcast;
use Pimple\Container;

class NotifierFactory
{
    /**
     * @var Container
     */
    private $container;

    /**
     * NotifierFactory constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function build(ScreenshotBroadcast $screenshotBroadcast)
    {
        $notifier = $screenshotBroadcast->attr('notifier');

        switch ($notifier) {
            case ScreenshotBroadcast::PUSHBULLET:
                return $this->container->offsetGet('pushbullet.notifier');
            case ScreenshotBroadcast::TELEGRAM:
                return $this->container->offsetGet('telegram.notifier');
        }
    }
}