<?php
/**
 * Created by PhpStorm.
 * User: inikulin
 * Date: 14.03.17
 * Time: 22:09
 */

namespace App\Service\ScreenshotStorage;


use App\Service\Browshot\Response\ScreenshotResponse;
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
     * @param ScreenshotResponse $screenshotResponse
     * @return bool
     */
    public function store(string $key, ScreenshotResponse $screenshotResponse): bool
    {
        $now = Carbon::now()->toDateTimeString();

        $result = $this->conn->insert('screenshot', [
            'screenshot_id' => $this->uuidFactory->uuid4()->toString(),
            'path'          => $key,
            'status'        => $screenshotResponse->status(),
            'browshot_id'   => $screenshotResponse->get('id'),
            'shooted_at'    => $now,
            'created_at'    => $now,
        ]);

        return $result > 0;
    }
}