<?php

/**
 * Created by PhpStorm.
 * User: inikulin
 * Date: 09.03.17
 * Time: 22:12
 */
class Kernel
{
    private $application;

    public function __construct($application)
    {
        $this->application = $application;

        foreach ($this->providers() as $provider) {
            $provider->register($application->);
        }
    }

    /**
     * @return \Pimple\ServiceProviderInterface[]
     */
    public function providers()
    {
        return [
            new \Provider\ConfigProvider(),
            new \Provider\DbProvider(),
            new \Provider\ScreenshotServiceProvider(),
        ];
    }

    public function getApplication()
    {
        return $this->application;
    }
}