<?php

use Phinx\Migration\AbstractMigration;

class AddScreenshotErrorColumn extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('screenshot');

        $table->addColumn('error', 'string');

        $table->update();
    }
}
