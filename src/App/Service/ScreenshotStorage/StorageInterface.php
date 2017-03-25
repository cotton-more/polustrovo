<?php

namespace App\Service\ScreenshotStorage;

use App\Service\Browshot\Response\ScreenshotSuccessResponse;

interface StorageInterface
{
    /**
     * Handle screenshot storing
     * @param string $key
     * @param ScreenshotSuccessResponse $response
     * @return bool
     */
    public function store(string $key, ScreenshotSuccessResponse $response): bool;
}