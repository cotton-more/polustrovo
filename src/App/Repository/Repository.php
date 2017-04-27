<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;

abstract class Repository
{
    /**
     * @var Connection
     */
    private $db;

    protected $tableName;

    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * ScreenshotRepository constructor.
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function insert(array $data)
    {
        return $this->getDb()->insert($this->getTableName(), $data);
    }

    /**
     * @return Connection
     */
    public function getDb()
    {
        return $this->db;
    }
}