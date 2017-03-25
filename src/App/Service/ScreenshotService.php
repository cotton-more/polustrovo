<?php

namespace App\Service;

use App\Screenshot;
use App\ScreenshotsDaily;
use App\ScreenshotStack;
use App\Service\Browshot\ApiClient;
use App\Service\Browshot\Response\ScreenshotErrorResponse;
use App\Service\Browshot\Response\ScreenshotResponse;
use App\Service\Browshot\Response\ScreenshotSuccessResponse;
use App\Service\Browshot\ScreenshotException;
use App\Service\ScreenshotStorage\StorageInterface;
use Carbon\Carbon;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOStatement;
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
     * @var Connection
     */
    private $db;

    /**
     * @var StorageInterface[]
     */
    private $screenshotStorageList;

    /**
     * ScreenshotService constructor.
     * @param ApiClient $client
     * @param Monolog $logger
     * @param Connection $db
     */
    public function __construct(ApiClient $client, Monolog $logger, Connection $db)
    {
        $this->client = $client;
        $this->logger = $logger;
        $this->db = $db;
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
        $sql = <<<SQL
SELECT s.* FROM screenshot s WHERE s.status NOT IN (?, ?) ORDER BY created_at ASC;
SQL;
        /** @var PDOStatement $stmt */
        $stmt = $this->db->executeQuery($sql, [
            ScreenshotResponse::STATUS_FINISHED,
            ScreenshotResponse::STATUS_ERROR,
        ]);

        /** @var Screenshot $screenshot */
        $screenshot = $stmt->fetchObject(Screenshot::class);

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

        $this->db->delete('screenshot', [
            'screenshot_id' => $screenshot->id(),
        ]);

        return true;
    }

    public function getLatest()
    {
        $sql = 'SELECT * FROM screenshot WHERE status = ? ORDER BY created_at DESC LIMIT 1';

        /** @var PDOStatement $stmt */
        $stmt = $this->db->executeQuery($sql, [ScreenshotResponse::STATUS_FINISHED]);

        $image = $stmt->fetchObject(Screenshot::class);

        return $image;
    }

    public function getCurrentWeek()
    {
        $monday = Carbon::today()->startOfWeek();
        $sql = 'SELECT * FROM screenshot WHERE created_at >= ? ORDER BY created_at ASC';

        /** @var PDOStatement $stmt */
        $stmt = $this->db->executeQuery($sql, [$monday]);

        $result = $stmt->fetchAll(\PDO::FETCH_CLASS, Screenshot::class);

        return $result;
    }

    /**
     * @return ScreenshotsDaily[]
     */
    public function getDaily()
    {
        $sql = <<<SQL
SELECT group_concat(screenshot_id) AS ids, count(*) AS count, date(shooted_at) AS date
FROM screenshot
GROUP BY date(shooted_at)
SQL;

        /** @var PDOStatement $stmt */
        $stmt = $this->db->executeQuery($sql);

        $result = $stmt->fetchAll(\PDO::FETCH_CLASS, ScreenshotsDaily::class);

        return $result;
    }

    public function getForDate($date)
    {
        $sql = 'SELECT * FROM screenshot WHERE created_at >= ? AND created_at < ? ORDER BY created_at ASC';

        $start = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
        $end = $start->copy()->addDay();

        /** @var PDOStatement $stmt */
        $stmt = $this->db->executeQuery($sql, [$start, $end]);

        $result = $stmt->fetchAll(\PDO::FETCH_CLASS, Screenshot::class);

        return $result;    }
}