<?php

namespace App\Service;

use App\Screenshot;
use App\ScreenshotsDaily;
use App\ScreenshotStack;
use App\Service\Browshot\ApiClient;
use App\Service\Browshot\Model\ScreenshotSimple;
use App\Service\Browshot\Response\ScreenshotErrorResponse;
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

        $response = $this->client->createScreenshot($url);

        if ($response instanceof ScreenshotErrorResponse) {
            $this->logger->warning('fail to get screenshot', $response->toArray());
            $this->logger->debug('end');
            return false;
        }

        $key = time().'_'.md5(random_bytes(8));

        $this->logger->debug('storing', ['key' => $key]);

        foreach ($this->screenshotStorageList as $screenshotStorage) {
            try {
                $screenshotStorage->store($key, $response);
            } catch (\Exception $ex) {
                $this->logger->warning($ex->getMessage());
            }
        }

        $this->logger->debug('end');
    }

    public function getLatest()
    {
        $sql = 'SELECT * FROM screenshot ORDER BY created_at DESC LIMIT 1';

        /** @var PDOStatement $stmt */
        $stmt = $this->db->executeQuery($sql);

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