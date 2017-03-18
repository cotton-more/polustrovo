<?php

namespace App\Service;

use App\Screenshot;
use App\ScreenshotsDaily;
use App\ScreenshotStack;
use App\Service\ScreenshotStorage\StorageInterface;
use Carbon\Carbon;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOStatement;
use Projek\Slim\Monolog;

class ScreenshotService
{
    /**
     * @var \Browshot
     */
    private $browshot;

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
     * @param \Browshot $browshot
     * @param Monolog $logger
     * @param Connection $db
     * @internal param string $url
     */
    public function __construct(\Browshot $browshot, Monolog $logger, Connection $db)
    {
        $this->browshot = $browshot;
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
     * @return void
     */
    public function take($url)
    {

        $this->logger->debug('taking '.$url);
        $data = $this->browshot->simple([
            'url' => $url,
            'instance_id' => 12,
            'cache' => 1,
        ]);

        $this->logger->debug('result '.$data['code']);

        if (200 === $data['code']) {
            $storeObj = $this->createStoreObject($data);
            $this->logger->debug('store', ['path' => $storeObj->path]);
            foreach ($this->screenshotStorageList as $screenshotStorage) {
                try {
                    $screenshotStorage->store($storeObj);
                } catch (\Exception $ex) {
                    $this->logger->warning($ex->getMessage());
                }
            }
        } else {
            $this->logger->error('Failed to get screenshot');
        }

        $this->logger->debug('end');
    }

    /**
     * @param array $data
     * @return \stdClass
     */
    private function createStoreObject(array $data)
    {
        $obj = new \stdClass();

        $obj->path = time().'_'.uniqid().'.png';
        $obj->image = $data['image'];

        return $obj;
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
}