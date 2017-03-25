<?php

namespace App\Service\ScreenshotStorage;

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
     * @param \App\Service\Browshot\Response\ScreenshotSuccessResponse $response
     * @return bool
     */
    public function store(string $key, ScreenshotSuccessResponse $response): bool
    {
        if (ScreenshotSuccessResponse::STATUS_FINISHED !== $response->status()) {
            return false;
        }

        $result = false;

        $path = $this->dir.'/'.$key;

        $client = new Client();
        $promise = $client->getAsync($response->get('screenshot_url'));
        $promise->then(function (ResponseInterface $res) use ($path, $result) {
            if (200 === $res->getStatusCode()) {
                $content = $res->getBody()->getContents();
                if (file_put_contents($path, $content)) {
                    $result = true;
                }
            }

            return $result;
        });
        $promise->wait();

        return $result;
    }
}