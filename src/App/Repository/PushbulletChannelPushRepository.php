<?php

namespace App\Repository;

use App\Entity\Screenshot;
use App\Entity\PushbulletChannelPush;
use Carbon\Carbon;
use Doctrine\DBAL\Driver\PDOStatement;

class PushbulletChannelPushRepository extends Repository
{
    public function getNext()
    {
        $sql = "SELECT * FROM pushbullet_channel_push WHERE published_at IS NULL";

        /** @var PDOStatement $stmt */
        $stmt = $this->getDb()->executeQuery($sql);

        $result = $stmt->fetchObject(PushbulletChannelPush::class);

        return $result;
    }

    public function markAsPublished($id)
    {
        $now = Carbon::now();

        $this->getDb()->update('pushbullet_channel_push', [
            'published_at' => $now->toDateTimeString(),
        ], ['id' => $id]);
    }

    public function getScreenshot($screenshotId)
    {
        $sql = <<<SQL
SELECT s.*
FROM screenshot s
JOIN pushbullet_channel_push pcp ON pcp.screenshot_id = s.screenshot_id
WHERE pcp.screenshot_id = ?
SQL;

        /** @var PDOStatement $stmt */
        $stmt = $this->getDb()->executeQuery($sql, [$screenshotId]);

        $result = $stmt->fetchObject(Screenshot::class);

        return $result;
    }
}