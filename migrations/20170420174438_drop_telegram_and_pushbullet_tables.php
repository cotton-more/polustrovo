<?php

use Phinx\Migration\AbstractMigration;

class DropTelegramAndPushbulletTables extends AbstractMigration
{
    public function up()
    {
        $this->dropTable('telegram_send_photo');
        $this->dropTable('pushbullet_channel_push');
    }
}
