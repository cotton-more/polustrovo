<?php

namespace App\Service\ScreenshotStorage;

interface StorageInterface
{
    /**
     * Handle screenshot storing
     *
     * @param \stdClass $data
     * @return bool
     */
    public function store(\stdClass $data);
}