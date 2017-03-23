<?php

namespace App\Service\Browshot\Response;

class ScreenshotResponse
{
    /**
     * @var mixed[]
     */
    private $container;

    /**
     * @param array $data
     */
    protected function __construct(array $data)
    {
        $this->container = $data;
    }

    /**
     * @return \mixed[]
     */
    public function toArray()
    {
        return $this->container;
    }

    public static function createSuccess(array $data): ScreenshotSuccessResponse
    {
        return ScreenshotSuccessResponse::fromArray($data);
    }

    public static function createError(array $data): ScreenshotErrorResponse
    {
        return ScreenshotErrorResponse::fromArray($data);
    }

    /**
     * @return mixed|null
     */
    public function status()
    {
        return $this->container['status'] ?? null;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($key)
    {
        return $this->container[$key] ?? null;
    }
}