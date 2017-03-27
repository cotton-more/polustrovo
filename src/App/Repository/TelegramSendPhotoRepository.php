<?php
/**
 * Created by PhpStorm.
 * User: inikulin
 * Date: 27.03.17
 * Time: 13:17
 */

namespace App\Repository;

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
}