<?php

namespace App\Provider;

use App\Model\Screenshot;
use Illuminate\Database\Capsule\Manager;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class DbProvider implements ServiceProviderInterface
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
        $pimple['db'] = function ($c) {
            $capsule = new Manager;
            $capsule->addConnection($c['config']['settings.db']);

            return $capsule;
        };

        $pimple['db']->setAsGlobal();
        $pimple['db']->bootEloquent();
    }
}