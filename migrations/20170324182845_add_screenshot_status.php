<?php

use Phinx\Migration\AbstractMigration;

class AddScreenshotStatus extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('screenshot');

        $table->addColumn('status', 'string');

        $table->update();
    }
}
