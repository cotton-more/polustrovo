<?php

namespace Providers;

use Illuminate\Config\Repository;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ConfigProvider implements ServiceProviderInterface
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
        $pimple['config'] = function (Container $c) {
            $config = new Repository();

            $path = $c['root.dir'].'/config/settings.php';
            $config->set(require $path);

            return $config;
        };
    }
}