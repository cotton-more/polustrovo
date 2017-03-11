<?php

namespace App\Service\ScreenshotStorage;

class FileStorage implements StorageInterface
{
    /**
     * @var string
     */
    private $dir;

    /**
     * EloquentStorage constructor.
     * @param $dir
     */
    public function __construct($dir)
    {
        $this->dir = $dir;
    }

    /**
     * @param \stdClass $data
     * @return bool
     */
    public function store(\stdClass $data)
    {
        $filename = $this->dir . '/' . $data->name;
        $result = file_put_contents($filename, $data->image);

        return $result > 0;
    }
}