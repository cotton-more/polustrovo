<?php

namespace Cli;

class ScreenshotService
{
    /**
     * @var \Browshot
     */
    private $browshot;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $screenshotDir;

    /**
     * ScreenshotService constructor.
     * @param \Browshot $browshot
     * @param string $url
     */
    public function __construct(\Browshot $browshot, $url)
    {
        $this->browshot = $browshot;
        $this->url = $url;
    }

    public function getScreenshotDir()
    {
        return $this->screenshotDir;
    }

    public function setScreenshotDir($screenshotDir)
    {
        $this->screenshotDir = $screenshotDir;
    }

    /**
     * @return string|false
     */
    public function take()
    {
        $data = $this->browshot->simple([
            'url' => $this->url,
            'instance_id' => 12,
            'cache' => 0,
        ]);

        if (200 === $data['code']) {
            return $this->saveScreenshot($data['image']);
        }

        return false;
    }

    /**
     * @param $image
     * @return string|false
     */
    private function saveScreenshot($image)
    {
        $path = $this->getScreenshotDir() . '/polustrovo-' . date('YmdHis') . '.png';

        if (file_put_contents($path, $image)) {
            return $path;
        }

        return false;
    }
}