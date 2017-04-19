<?php

use Phinx\Migration\AbstractMigration;

class AddPushbulletChannelPushTable extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('pushbullet_channel_push');

        $table->addColumn('channel', 'string');
        $table->addColumn('path', 'string');
        $table->addColumn('screenshot_id', 'uuid');
        $table->addColumn('published_at', 'timestamp');
        $table->addColumn('created_at', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP',
            'update' => ''
        ]);

        $table->create();
    }
}
