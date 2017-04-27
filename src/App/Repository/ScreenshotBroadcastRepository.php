<?php

namespace App\Repository;

use App\Entity\Screenshot;
use App\Entity\ScreenshotBroadcast;
use Carbon\Carbon;
use Doctrine\DBAL\Driver\PDOStatement;

class ScreenshotBroadcastRepository extends Repository
{
    protected $tableName = 'screenshot_broadcast';

    public function getNext()
    {
        $sql = "SELECT * FROM screenshot_broadcast WHERE published_at IS NULL";

        /** @var PDOStatement $stmt */
        $stmt = $this->getDb()->executeQuery($sql);

        $result = $stmt->fetchObject(ScreenshotBroadcast::class);

        return $result;
    }

    public function markAsPublished($id)
    {
        $now = Carbon::now();

        $this->getDb()->update('screenshot_broadcast', [
            'published_at' => $now->toDateTimeString(),
        ], ['id' => $id]);
    }

    public function getScreenshot($id)
    {
        $sql = <<<SQL
SELECT s.*
FROM screenshot s
JOIN screenshot_broadcast sb ON sb.screenshot_id = s.screenshot_id
WHERE sb.id = ?
SQL;

        /** @var PDOStatement $stmt */
        $stmt = $this->getDb()->executeQuery($sql, [$id]);

        $result = $stmt->fetchObject(Screenshot::class);

        return $result;
    }

    public function insert(array $data)
    {
        return $this->getDb()->insert('screenshot_broadcast', $data);
    }
}