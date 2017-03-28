<?php

use Phinx\Migration\AbstractMigration;

class AddTelegramSendPhotoTable extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('telegram_send_photo');

        $table->addColumn('chat_id', 'string');
        $table->addColumn('path', 'string');
        $table->addColumn('screenshot_id', 'uuid');
        $table->addColumn('published_at', 'timestamp');
        $table->addColumn('created_at', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP',
            'update' => ''
        ]);

        $table->addIndex('screenshot_id');

        $table->create();
    }
}
