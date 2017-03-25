<?php

namespace App\Provider;

use App\Http\IndexController;
use App\Model\Screenshot;
use App\Repository\ScreenshotRepository;
use App\Service\Browshot\ApiClient;
use App\Service\Browshot\Configuration;
use App\Service\GlideScreenshotService;
use App\Service\ScreenshotService;
use App\Service\ScreenshotStorage\DoctrineStorage;
use App\Service\ScreenshotStorage\FileStorage;
use League\Glide\Responses\SlimResponseFactory;
use League\Glide\ServerFactory as GlideServerFactory;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Ramsey\Uuid\Uuid;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

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
        $pimple['screenshot.repository'] = function (Container $c) {
            $repository = new ScreenshotRepository($c['db']);

            return $repository;
        };

        $pimple['browshot.configuration'] = function (Container $c) {
            $config = Configuration::getDefaultConfiguration();
            $config->setApiKey($c['config']['browshot.api_key']);
            $config->setInstanceId($c['config']['browshot.instance_id']);

            return $config;
        };

        $pimple['browshot.api_client'] = function (Container $c) {
            $apiClient = new ApiClient($c['browshot.configuration']);

            return $apiClient;
        };

        $pimple['screenshot.file_storage'] = function (Container $c) {
            $storage = new FileStorage($c['config']['screenshot_dir']);

            return $storage;
        };

        $pimple['screenshot.doctrine_storage'] = function (Container $c) {
            $storage = new DoctrineStorage($c['db'], $c['uuid.factory']);

            return $storage;
        };

        $pimple['screenshot'] = function (Container $c) {
            $service = new ScreenshotService($c['browshot.api_client'], $c['logger'], $c['db']);

            $service->addScreenshotStorage($c['screenshot.file_storage']);
            $service->addScreenshotStorage($c['screenshot.doctrine_storage']);

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
                $c['glide.screenshot.server'], // \League\Glide\Server
                $c['db']
            );

            return $service;
        };

        $pimple['view'] = function (Container $c) {
            $view = new Twig($c['settings']['view']['templates'], [
                'cache' => $c['settings']['view']['cache'],
            ]);

            $view->getEnvironment()->enableDebug();

            $view->addExtension(new \Twig_Extension_Debug());

            // Instantiate and add Slim specific extension
            $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
            $view->addExtension(new TwigExtension($c['router'], $basePath));

            return $view;
        };

        $pimple['uuid.factory'] = function () {
            return Uuid::getFactory();
        };
    }
}