<?php

namespace App\Service\ScreenshotStorage;

use App\Service\Browshot\Response\ScreenshotResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Projek\Slim\Monolog;

class FileStorage implements StorageInterface
{
    /**
     * @var string
     */
    private $dir;

    /**
     * @var Monolog
     */
    private $logger;

    /**
     * EloquentStorage constructor.
     * @param $dir
     * @param Monolog $logger
     */
    public function __construct($dir, Monolog $logger)
    {
        $this->dir = $dir;
        $this->logger = $logger;
    }

    public function getPriority(): int
    {
        return 500;
    }

    /**
     * @param ScreenshotResponse $response
     * @return bool
     */
    public function store(ScreenshotResponse $response): bool
    {
        $this->logger->debug('store to a file', $response->toArray());
        if (!$response->isStatusFinished()) {
            $this->logger->debug('response not finished');
            return false;
        }

        $result = false;

        $filename = $response->getFilename() ?: $this->generateFilename($response);

        $this->logger->debug('setting filename', [
            'filename' => $filename,
        ]);

        $client = new Client();
        try {
            $res = $client->request('GET', $response->get('screenshot_url'));
        } catch (ClientException $ex) {
            $this->logger->warning('client error', [
                'message' => $ex->getMessage(),
                'code' => $ex->getCode(),
            ]);

            $response->setError($ex->getMessage(), $ex->getCode());
            return false;
        }

        $content = $res->getBody()->getContents();
        if ($size = file_put_contents($this->dir.'/'.$filename, $content)) {
            $response->setFilename($filename);
            $result = true;
        }

        $this->logger->debug('end', [
            'size' => $size,
            'result' => $result,
        ]);

        return $result;
    }

    /**
     * @param ScreenshotResponse $response
     * @return string
     */
    public function generateFilename(ScreenshotResponse $response): string
    {
        $filename = time().'_'.$response->get('id');

        return $filename;
    }
}