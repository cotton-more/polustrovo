<?php

namespace App\Provider;

use App\Model\Screenshot;
use App\Service\GlideScreenshotService;
use App\Service\ScreenshotService;
use App\Service\ScreenshotStorage\EloquentStorage;
use App\Service\ScreenshotStorage\FileStorage;
use League\Glide\Responses\SlimResponseFactory;
use League\Glide\ServerFactory as GlideServerFactory;
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

        $pimple['glide.screenshot.server'] = function (Container $c) {
            $cacheDir = $c['config']['cache_dir'].'/glide';
            if (false === is_dir($cacheDir)) {
                mkdir($c['config']['cache_dir'].'/glide');
            }

            $server = GlideServerFactory::create([
                'source' => $c['config']['screenshot_dir'],
                'cache' => $cacheDir,
                'response' => new SlimResponseFactory(),
            ]);

            return $server;
        };

        $pimple['glide.screenshot'] = function (Container $c) {
            $service = new GlideScreenshotService(
                $c['glide.screenshot.server'] // \League\Glide\Server
            );

            return $service;
        };
    }
}