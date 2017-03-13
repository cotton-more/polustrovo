<?php

namespace App\Service;

use Illuminate\Database\Capsule\Manager;
use League\Glide\Server as GlideServer;

class GlideScreenshotService
{
    /**
     * @var GlideServer
     */
    private $server;

    /**
     * @var Manager
     */
    private $db;

    public function __construct(GlideServer $server, Manager $db)
    {
        $this->server = $server;
        $this->db = $db;
    }

    /**
     * @param $screenshotId
     * @return mixed
     */
    public function imageResponse($screenshotId)
    {
        $image = $this->db->table('screenshot')->where('screenshot_id', $screenshotId)->first();

        $this->db->table('screenshot')->where('screenshot_id', $screenshotId)->update([
            'is_new' => 0,
        ]);

        return $this->server->getImageResponse($image->name, []);
    }
}