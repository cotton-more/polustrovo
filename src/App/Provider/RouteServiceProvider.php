<?php

namespace App\Provider;

use App\Http\IndexController;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class RouteServiceProvider implements ServiceProviderInterface
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
        $pimple['index_controller'] = function (Container $c) {
            $controller = new IndexController($c);

            return $controller;
        };
    }
}