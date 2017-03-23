<?php

namespace App\Service\Browshot\Response;

class ScreenshotSuccessResponse extends ScreenshotResponse
{
    const STATUS_IN_QUEUE   = 'in_queue';
    const STATUS_PROCESSING = 'processing';
    const STATUS_FINISHED   = 'finished';
    const STATUS_ERROR      = 'error';

    /**
     * @param  mixed[] $data
     * @return ScreenshotSuccessResponse
     */
    public static function fromArray(array $data): ScreenshotSuccessResponse
    {
        return new static($data);
    }
}