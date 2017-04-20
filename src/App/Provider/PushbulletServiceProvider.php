<?php

namespace App\Provider;

use App\Repository\PushbulletChannelPushRepository;
use App\Service\Notifier\PushbulletNotifier;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Pushbullet\Pushbullet;

class PushbulletServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['pushbullet.notifier'] = function (Container $c) {
            $notifier = new PushbulletNotifier(
                $c['pushbullet'],
                $c['config']['pushbullet.channel'],
                $c['repository.screenshot_broadcast']
            );

            return $notifier;
        };

        $pimple['pushbullet'] = function (Container $c) {
            $pushbullet = new Pushbullet($c['config']['pushbullet.access_token']);

            return $pushbullet;
        };
    }
}