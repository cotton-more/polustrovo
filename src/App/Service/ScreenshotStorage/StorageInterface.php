<?php

namespace App\Service\ScreenshotStorage;

use App\Service\Browshot\Response\ScreenshotResponse;

interface StorageInterface
{
    /**
     * Handle screenshot storing
     * @param ScreenshotResponse $response
     * @return bool
     */
    public function store(ScreenshotResponse $response): bool;

    /**
     * Storage priority
     * @return int
     */
    public function getPriority(): int;
}