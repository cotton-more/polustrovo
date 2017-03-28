<?php

namespace App\Repository;

use App\Entity\Screenshot;
use App\Entity\TelegramSendPhoto;
use Carbon\Carbon;
use Doctrine\DBAL\Driver\PDOStatement;

class TelegramSendPhotoRepository extends Repository
{
    public function getNext()
    {
        $sql = "SELECT * FROM telegram_send_photo WHERE published_at IS NULL";

        /** @var PDOStatement $stmt */
        $stmt = $this->getDb()->executeQuery($sql);

        $result = $stmt->fetchObject(TelegramSendPhoto::class);

        return $result;
    }

    public function markAsPublished($id)
    {
        $now = Carbon::now();

        $this->getDb()->update('telegram_send_photo', [
            'published_at' => $now->toDateTimeString(),
        ], ['id' => $id]);
    }

    public function getScreenshot($screenshotId)
    {
        $sql = <<<SQL
SELECT s.*
FROM screenshot s
JOIN telegram_send_photo tsp ON tsp.screenshot_id = s.screenshot_id
WHERE tsp.screenshot_id = ?
SQL;

        /** @var PDOStatement $stmt */
        $stmt = $this->getDb()->executeQuery($sql, [$screenshotId]);

        $result = $stmt->fetchObject(Screenshot::class);

        return $result;
    }
}