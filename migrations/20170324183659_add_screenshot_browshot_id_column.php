<?php

use Phinx\Migration\AbstractMigration;

class AddScreenshotBrowshotIdColumn extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('screenshot');

        $table->addColumn('browshot_id', 'string');

        $table->update();
    }
}
