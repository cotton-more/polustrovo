<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;

abstract class Repository
{
    /**
     * @var Connection
     */
    private $db;

    /**
     * ScreenshotRepository constructor.
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @return Connection
     */
    public function getDb()
    {
        return $this->db;
    }
}