<?php

namespace App\Service;

use App\Repository\ScreenshotRepository;
use App\Service\Browshot\ApiClient;
use App\Service\Browshot\Response\ScreenshotResponse;
use App\Service\ScreenshotStorage\StorageInterface;
use App\Service\ScreenshotStorage\StoragePriorityQueue;
use Projek\Slim\Monolog;

class ScreenshotService
{
    const CACHE_24_HOURS = 86400;

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
     * @var StoragePriorityQueue
     */
    private $storageQueue;

    /**
     * ScreenshotService constructor.
     * @param ApiClient $client
     * @param Monolog $logger
     * @param ScreenshotRepository $repository
     * @param StoragePriorityQueue $storageQueue
     */
    public function __construct(
        ApiClient $client,
        Monolog $logger,
        ScreenshotRepository $repository,
        StoragePriorityQueue $storageQueue
    ) {
        $this->client = $client;
        $this->logger = $logger;
        $this->repository = $repository;
        $this->storageQueue = $storageQueue;
    }

    /**
     * @param string $url
     * @param int $cache
     * @return bool
     */
    public function take($url, int $cache = null)
    {
        $cache = filter_var(
            $cache,
            FILTER_VALIDATE_INT,
            [
                'options' => [
                    'default'   => self::CACHE_24_HOURS,
                    'min_range' => 0,
                ],
            ]
        );

        $this->logger->debug('start', ['url' => $url, 'cache' => $cache]);

        /** @var ScreenshotResponse $response */
        $response = $this->client->createScreenshot($url, [
            'cache' => $cache,
        ]);

        if (!$response->isSuccess()) {
            $this->logger->warning('fail to get screenshot', $response->toArray());
            $this->logger->debug('end');
        }

        $this->store($response);
        $this->store($response);

        $this->logger->debug('end');

        return true;
    }

    /**
     * @param ScreenshotResponse $response
     */
    public function store(ScreenshotResponse $response)
    {
        /** @var StorageInterface $screenshotStorage */
        foreach ($this->storageQueue as $screenshotStorage) {
            try {
                $screenshotStorage->store($response);
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