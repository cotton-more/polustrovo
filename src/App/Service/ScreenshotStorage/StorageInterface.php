<?php

namespace App\Service\ScreenshotStorage;

use App\Service\Browshot\Response\ScreenshotResponse;

interface StorageInterface
{
    /**
     * Handle screenshot storing
     * @param string $key
     * @param ScreenshotResponse $response
     * @return bool
     */
    public function store(string $key, ScreenshotResponse $response): bool;
}