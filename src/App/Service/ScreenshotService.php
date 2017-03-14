<?php

namespace App\Service;

use App\Model\Screenshot;
use App\Service\ScreenshotStorage\StorageInterface;
use Illuminate\Database\Capsule\Manager;
use Projek\Slim\Monolog;

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
     * @var Monolog
     */
    private $logger;

    /**
     * ScreenshotService constructor.
     * @param \Browshot $browshot
     * @param string $url
     * @param Monolog $logger
     */
    public function __construct(\Browshot $browshot, $url, Monolog $logger)
    {
        $this->browshot = $browshot;
        $this->url = $url;
        $this->logger = $logger;
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
        $this->logger->debug('taking '.$this->url);
        $data = $this->browshot->simple([
            'url' => $this->url,
            'instance_id' => 12,
            'cache' => 1,
        ]);

        $this->logger->debug('result '.$data['code']);

        if (200 === $data['code']) {
            $storeObj = $this->createStoreObject($data);
            $this->logger->debug('store', ['path' => $storeObj->path]);
            foreach ($this->screenshotStorageList as $screenshotStorage) {
                try {
                    $screenshotStorage->store($storeObj);
                } catch (\Exception $ex) {
                    $this->logger->warning($ex->getMessage());
                }
            }
        } else {
            $this->logger->error('Failed to get screenshot');
        }

        $this->logger->debug('end');
    }

    /**
     * @param array $data
     * @return \stdClass
     */
    private function createStoreObject(array $data)
    {
        $obj = new \stdClass();

        $obj->path = time().'_'.uniqid().'.png';
        $obj->image = $data['image'];

        return $obj;
    }
}