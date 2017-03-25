<?php

namespace App\Service\ScreenshotStorage;

use App\Service\Browshot\Response\ScreenshotSuccessResponse;
use Carbon\Carbon;
use Doctrine\DBAL\Connection;
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

    public function __construct(Connection $conn, UuidFactoryInterface $uuidFactory)
    {
        $this->conn = $conn;
        $this->uuidFactory = $uuidFactory;
    }

    public function getName()
    {
        return 'db';
    }

    /**
     * Handle screenshot storing
     *
     * @param string $key
     * @param ScreenshotSuccessResponse $screenshotResponse
     * @return bool
     */
    public function store(string $key, ScreenshotSuccessResponse $screenshotResponse): bool
    {
        $now = Carbon::now();

        $data = [
            'screenshot_id' => $this->uuidFactory->uuid4()->toString(),
            'status'        => $screenshotResponse->status(),
            'browshot_id'   => $screenshotResponse->id(),
            'created_at'    => $now->toDateTimeString(),
        ];

        // add shooted time and path to a file
        if (ScreenshotSuccessResponse::STATUS_FINISHED === $screenshotResponse->status()) {
            $finishedAt = floor($screenshotResponse->get('finished') / 1000);
            $shootedAt = Carbon::createFromTimestamp($finishedAt);
            $data['shooted_at'] = $shootedAt->toDateTimeString();

            $data['path'] = $key;
        }

        $result = $this->conn->insert('screenshot', $data);

        return $result > 0;
    }
}