<?php

namespace App\Service;

use League\Glide\Server as GlideServer;

class GlideScreenshotService
{
    /**
     * @var GlideServer
     */
    private $server;

    public function __construct(GlideServer $server)
    {
        $this->server = $server;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function imageResponse($name)
    {
        $path = $name.'.png';

        return $this->server->getImageResponse($path, []);
    }
}