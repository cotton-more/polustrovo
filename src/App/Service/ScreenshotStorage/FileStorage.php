<?php

namespace App\Service\ScreenshotStorage;

use App\Service\Browshot\Response\ScreenshotResponse;
use App\Service\Browshot\Response\ScreenshotSuccessResponse;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class FileStorage implements StorageInterface
{
    /**
     * @var string
     */
    private $dir;

    /**
     * EloquentStorage constructor.
     * @param $dir
     */
    public function __construct($dir)
    {
        $this->dir = $dir;
    }

    public function getName()
    {
        return 'file';
    }

    /**
     * @param string $key
     * @param \App\Service\Browshot\Response\ScreenshotResponse $response
     * @return bool
     */
    public function store(string $key, ScreenshotResponse $response): bool
    {
        if (ScreenshotSuccessResponse::STATUS_FINISHED !== $response->status()) {
            return false;
        }

        $path = $this->dir.'/'.$key;

        $client = new Client();
        $client->get($response->get('screenshot_url'), [
            'sink' => $path,
        ]);

        return true;
    }
}