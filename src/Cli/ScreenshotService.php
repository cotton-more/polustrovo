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

    public function take()
    {
        $data = $this->browshot->simple([
            'url' => $this->url,
            'instance_id' => 12,
        ]);

        if (200 === $data['code']) {
            $this->saveScreenshot($data['image']);
        }
    }

    /**
     * @param $image
     * @return int
     */
    private function saveScreenshot($image)
    {
        $path = $this->getScreenshotDir() . '/plustrovo-' . date('YmdHis') . '.png';

        return file_put_contents($path, $image);
    }
}