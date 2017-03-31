<?php

namespace App\Service\ScreenshotStorage;

use App\Service\Browshot\Response\ScreenshotResponse;
use Carbon\Carbon;
use Doctrine\DBAL\Connection;
use Projek\Slim\Monolog;
use Ramsey\Uuid\UuidFactoryInterface;

class DoctrineStorage implements StorageInterface
{
    /**
     * @var Connection
     */
    private $conn;

    /**
     * @var UuidFactoryInterface
     */
    private $uuidFactory;

    /**
     * @var Monolog
     */
    private $logger;

    /**
     * DoctrineStorage constructor.
     * @param Connection $conn
     * @param UuidFactoryInterface $uuidFactory
     * @param Monolog $logger
     */
    public function __construct(Connection $conn, UuidFactoryInterface $uuidFactory, Monolog $logger)
    {
        $this->conn = $conn;
        $this->uuidFactory = $uuidFactory;
        $this->logger = $logger;
    }

    public function getPriority(): int
    {
        return 900;
    }

    /**
     * Handle screenshot storing
     *
     * @param ScreenshotResponse $screenshotResponse
     * @return bool
     */
    public function store(ScreenshotResponse $screenshotResponse): bool
    {
        $this->logger->debug('store to database', $screenshotResponse->toArray());
        $now = Carbon::now();

        $error = $screenshotResponse->error();

        $data = [
            'status'        => $screenshotResponse->status(),
            'browshot_id'   => $screenshotResponse->id(),
            'created_at'    => $now->toDateTimeString(),
            'path'          => $screenshotResponse->getFilename(),
            'error'         => $error,
        ];

        // requeue if status is finished but there is an error
        if ($screenshotResponse->isStatusFinished() && $error) {
            $data['status'] = ScreenshotResponse::STATUS_IN_QUEUE;
            $this->logger->warning('requeue screenshot response', $screenshotResponse->toArray());
        }

        $screenshotId = $this->uuidFactory->uuid4()->toString();

        $data['screenshot_id'] = $screenshotId;

        if ($finishedAt = $screenshotResponse->finished()) {
            $data['shooted_at'] = $finishedAt->toDateTimeString();
        }

        $this->logger->debug('set data', $data);

        $result = $this->conn->insert('screenshot', $data);

        if ($result) {
            $screenshotResponse->setScreenshotId($screenshotId);
        }

        $this->logger->debug('end', [
            'result' => $result,
        ]);

        return $result > 0;
    }
}