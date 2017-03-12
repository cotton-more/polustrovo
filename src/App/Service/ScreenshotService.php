<?php

namespace App\Service;

use App\Model\Screenshot;
use App\Service\ScreenshotStorage\StorageInterface;

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
     * @var StorageInterface[]
     */
    private $screenshotStorageList;

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

    /**
     * @param StorageInterface $storage
     */
    public function addScreenshotStorage(StorageInterface $storage)
    {
        $this->screenshotStorageList[] = $storage;
    }

    /**
     * @return string|false
     */
    public function take()
    {
        $data = $this->browshot->simple([
            'url' => $this->url,
            'instance_id' => 12,
            'cache' => 1,
        ]);

        if (200 === $data['code']) {
            $storeObj = $this->createStoreObject($data);
            foreach ($this->screenshotStorageList as $screenshotStorage) {
                try {
                    $screenshotStorage->store($storeObj);
                } catch (\Exception $ex) {
                    // todo: log exception
                }
            }
        }

        return false;
    }

    /**
     * @param array $data
     * @return \stdClass
     */
    private function createStoreObject(array $data)
    {
        $obj = new \stdClass();

        $obj->name = date('Ymd').'_'.uniqid().'.png';
        $obj->image = $data['image'];

        return $obj;
    }
}