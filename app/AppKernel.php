<?php

/**
 * Created by PhpStorm.
 * User: inikulin
 * Date: 09.03.17
 * Time: 22:12
 */
class AppKernel
{
    private $container;

    /**
     * AppKernel constructor.
     * @param \Pimple\Container $container
     */
    public function __construct($container)
    {
        $dotenv = new \Dotenv\Dotenv(__DIR__.'/..');
        $dotenv->load();

        $this->container = $container;

        $this->register();
        $this->boot();
    }

    /**
     * @return \Pimple\ServiceProviderInterface[]
     */
    public function providers()
    {
        return [
            new \Projek\Slim\MonologProvider(),
            new App\Provider\ConfigProvider(),
            new App\Provider\DbalProvider(),
            new App\Provider\AppServiceProvider(),
            new App\Provider\RouteServiceProvider(),
        ];
    }

    public function register()
    {
        foreach ($this->providers() as $provider) {
            $provider->register($this->container);
        }
    }

    public function boot()
    {
        foreach ($this->providers() as $provider) {
            if (method_exists($provider, 'boot')) {
                call_user_func([$provider, 'boot'], $this->container);
            }
        }
    }
}