<?php

namespace App\Service;

use App\Repository\ScreenshotRepository;
use App\Service\Browshot\ApiClient;
use App\Service\Browshot\Response\ScreenshotResponse;
use App\Service\ScreenshotStorage\StorageInterface;
use Projek\Slim\Monolog;

class ScreenshotService
{
    /**
     * @var ApiClient
     */
    private $client;

    /**
     * @var Monolog
     */
    private $logger;

    /**
     * @var ScreenshotRepository
     */
    private $repository;

    /**
     * @var StorageInterface[]
     */
    private $screenshotStorageList;

    /**
     * ScreenshotService constructor.
     * @param ApiClient $client
     * @param Monolog $logger
     * @param ScreenshotRepository $repository
     */
    public function __construct(
        ApiClient $client,
        Monolog $logger,
        ScreenshotRepository $repository
    ) {
        $this->client = $client;
        $this->logger = $logger;
        $this->repository = $repository;
    }

    /**
     * @param StorageInterface $storage
     */
    public function addScreenshotStorage(StorageInterface $storage)
    {
        $this->screenshotStorageList[] = $storage;
    }

    /**
     * @param string $url
     * @return bool
     */
    public function take($url)
    {
        $this->logger->debug('start', ['url' => $url]);

        /** @var ScreenshotResponse $response */
        $response = $this->client->createScreenshot($url);

        if (!$response->isSuccess()) {
            $this->logger->warning('fail to get screenshot', $response->toArray());
            $this->logger->debug('end');
        }

        $this->store($response);

        $this->logger->debug('end');

        return true;
    }

    /**
     * @param ScreenshotResponse $response
     */
    public function store(ScreenshotResponse $response)
    {
        $key = time().'_'.$response->get('id');

        $this->logger->debug('storing', ['key' => $key]);

        foreach ($this->screenshotStorageList as $screenshotStorage) {
            try {
                $screenshotStorage->store($key, $response);
            } catch (\Exception $ex) {
                $this->logger->warning($ex->getMessage());
            }
        }
    }

    public function download()
    {
        $screenshot = $this->repository->getQueued();

        if (false === $screenshot) {
            $this->logger->debug('no queued screenshots');
            return false;
        }

        $id = $screenshot->attr('browshot_id');
        $this->logger->debug('download screenshot', [
            'id' => $id,
        ]);

        $response = $this->client->screenshotInfo($id);

        if (!$response->isSuccess()) {
            $this->logger->error($response->get('error'), $response->toArray());
            return false;
        }

        if (ScreenshotResponse::STATUS_FINISHED !== $response->get('status')) {
            $this->logger->debug('screenshot not ready', [
                'status' => $response->get('status'),
            ]);
            return false;
        }

        $this->store($response);

        $this->repository->deleteBy([
            'screenshot_id' => $screenshot->id(),
        ]);

        return true;
    }
}