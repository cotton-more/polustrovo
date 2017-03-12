<?php

/**
 * Created by PhpStorm.
 * User: inikulin
 * Date: 09.03.17
 * Time: 22:12
 */
class AppKernel
{
    public function __construct()
    {
        $dotenv = new \Dotenv\Dotenv(__DIR__.'/..');
        $dotenv->load();
    }

    /**
     * @return \Pimple\ServiceProviderInterface[]
     */
    public function providers()
    {
        return [
            new App\Provider\ConfigProvider(),
            new App\Provider\DbProvider(),
            new App\Provider\AppServiceProvider(),
        ];
    }

    public function register(\Pimple\Container $container)
    {
        foreach ($this->providers() as $provider) {
            $provider->register($container);
        }
    }

    public function boot(\Pimple\Container $container)
    {
        foreach ($this->providers() as $provider) {
            if (method_exists($provider, 'boot')) {
                call_user_func([$provider, 'boot'], $container);
            }
        }
    }
}