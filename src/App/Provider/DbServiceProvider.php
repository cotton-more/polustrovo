<?php

namespace App\Provider;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class DbServiceProvider implements ServiceProviderInterface
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
        $pimple['db.options'] = [
            'driver' => 'pdo_sqlite',
            'path' => $pimple['config']['settings.db.database'],
        ];

        $pimple['db.config'] = function () {
            return new Configuration();
        };

        $pimple['db.event_manager'] = function () {
            return new EventManager();
        };

        $pimple['db'] = function (Container $c) {
            $options = $c['db.options'];
            $config = $c['db.config'];
            $manager = $c['db.event_manager'];

            return DriverManager::getConnection($options, $config, $manager);
        };
    }
}