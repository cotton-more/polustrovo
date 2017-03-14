<?php

use Phinx\Migration\AbstractMigration;

class CreateScreenshotTable extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('screenshot', [
            'id' => false,
            'primary_key' => ['screenshot_id'],
        ]);

        $table->addColumn('screenshot_id', 'uuid');
        $table->addColumn('path', 'string');
        $table->addColumn('data', 'text');
        $table->addColumn('shooted_at', 'datetime');
        $table->addColumn('created_at', 'datetime');

        $table->create();
    }
}
