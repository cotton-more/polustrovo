<?php

namespace App\Service\Browshot;

class Configuration
{
    /**
     * @var Configuration
     */
    private static $defaultConfiguration;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $host = 'https://api.browshot.com/api/v1/';

    /**
     * @var int
     */
    protected $instanceId;

    /**
     * Gets the default configuration instance
     *
     * @return Configuration
     */
    public static function getDefaultConfiguration(): Configuration
    {
        if (self::$defaultConfiguration === null) {
            self::$defaultConfiguration = new static();
        }

        return self::$defaultConfiguration;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @return Configuration
     */
    public function setApiKey(string $apiKey): Configuration
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return Configuration
     */
    public function setHost(string $host): Configuration
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return int
     */
    public function getInstanceId(): int
    {
        return $this->instanceId;
    }

    /**
     * @param int $instanceId
     * @return Configuration
     */
    public function setInstanceId(int $instanceId): Configuration
    {
        $this->instanceId = $instanceId;
        return $this;
    }
}