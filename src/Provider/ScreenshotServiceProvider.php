<?php

namespace Providers;

use Cli\ScreenshotService;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ScreenshotServiceProvider implements ServiceProviderInterface
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
        $pimple['screenshot_service'] = function (Container $c) {
            $browshot = new \Browshot(
                $c['config']['browshot_key'], 0, 'https://browshot.com/api/v1/'
            );

            $service = new ScreenshotService($browshot, $c['config']['url']);
            $service->setScreenshotDir($c['config']['screenshot_dir']);

            return $service;
        };
    }
}