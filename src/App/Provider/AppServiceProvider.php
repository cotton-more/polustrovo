<?php

namespace App\Provider;

use App\Model\Screenshot;
use App\Service\ScreenshotService;
use App\Service\ScreenshotStorage\EloquentStorage;
use App\Service\ScreenshotStorage\FileStorage;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class AppServiceProvider implements ServiceProviderInterface
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
        $pimple['browshot'] = function (Container $c) {
            $browshot = new \Browshot(
                $c['config']['browshot_key'], 0, 'https://browshot.com/api/v1/'
            );

            return $browshot;
        };

        $pimple['screenshot.file_storage'] = function (Container $c) {
            $storage = new FileStorage($c['config']['screenshot_dir']);

            return $storage;
        };

        $pimple['screenshot.eloquent_storage'] = function (Container $c) {
            $model = new Screenshot();
            $storage = new EloquentStorage($model);

            return $storage;
        };

        $pimple['screenshot_service'] = function (Container $c) {
            $service = new ScreenshotService($c['browshot'], $c['config']['url'], $c['logger']);

            $service->addScreenshotStorage($c['screenshot.file_storage']);
            $service->addScreenshotStorage($c['screenshot.eloquent_storage']);

            return $service;
        };
    }
}