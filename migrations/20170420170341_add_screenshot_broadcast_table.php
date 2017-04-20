<?php

use Phinx\Migration\AbstractMigration;

class AddScreenshotBroadcastTable extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('screenshot_broadcast');

        $table->addColumn('notifier', 'string');
        $table->addColumn('target', 'string');
        $table->addColumn('screenshot_id', 'uuid');
        $table->addColumn('published_at', 'timestamp');
        $table->addColumn('created_at', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP',
            'update' => ''
        ]);

        $table->create();
    }
}
