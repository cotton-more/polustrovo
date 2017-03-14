<?php

namespace App\Service;

use Doctrine\DBAL\Connection;
use League\Glide\Server as GlideServer;
use Psr\Http\Message\ResponseInterface;

class GlideScreenshotService
{
    /**
     * @var GlideServer
     */
    private $server;

    /**
     * @var Connection
     */
    private $conn;

    public function __construct(GlideServer $server, Connection $conn)
    {
        $this->server = $server;
        $this->conn = $conn;
    }

    /**
     * @param $screenshotId
     * @param array $params
     * @return ResponseInterface
     */
    public function imageResponse($screenshotId, array $params = [])
    {
        $image = $this->conn->fetchAssoc('SELECT * FROM screenshot WHERE screenshot_id = ?', [
            $screenshotId
        ]);

        return $this->server->getImageResponse($image['path'], $params);
    }
}