<?php

namespace App\Service\ScreenshotStorage;

use App\Repository\ScreenshotBroadcastRepository;
use App\Service\Browshot\Response\ScreenshotResponse;
use App\Service\Notifier\PushbulletNotifier;
use Projek\Slim\Monolog;

class PushbulletStorage implements StorageInterface
{

    /**
     * @var ScreenshotBroadcastRepository
     */
    private $repository;

    /**
     * @var Monolog
     */
    private $logger;

    private $notifier;

    /**
     * PushbulletStorage constructor.
     * @param PushbulletNotifier $notifier
     * @param ScreenshotBroadcastRepository $repository
     * @param Monolog $logger
     */
    public function __construct(
        PushbulletNotifier $notifier,
        ScreenshotBroadcastRepository $repository,
        Monolog $logger
    ) {
        $this->notifier = $notifier;
        $this->repository = $repository;
        $this->logger = $logger;
    }

    /**
     * Handle screenshot storing
     * @param ScreenshotResponse $response
     * @return bool
     */
    public function store(ScreenshotResponse $response): bool
    {

        $this->logger->debug('store to pushbullet');

        $data = [
            'target'        => $this->notifier->getChannel(),
            'screenshot_id' => $response->getScreenshotId() ?: null,
            'notifier'      => 'pushbullet',
        ];

        $this->logger->debug('data', $data);

        $result = null;
        if ($response->isStatusFinished() && $response->isSuccess()) {
            $result = $this->repository->insert($data);
        } else {
            $this->logger->debug('invalid response', [
                'status' => $response->status(),
                'code' => $response->code(),
                'error' => $response->error(),
            ]);
        }

        $this->logger->debug('end', [
            'result' => $result,
        ]);

        return $result > 0;
    }

    /**
     * Storage priority
     * @return int
     */
    public function getPriority(): int
    {
        return 10;
    }
}