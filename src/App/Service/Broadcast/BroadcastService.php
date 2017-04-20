<?php

namespace App\Service\Broadcast;

use App\Repository\ScreenshotBroadcastRepository;
use App\Service\Notifier\NotifierFactory;

class BroadcastService
{
    /**
     * @var ScreenshotBroadcastRepository
     */
    private $repository;

    /**
     * @var NotifierFactory
     */
    private $factory;

    /**
     * BroadcastService constructor.
     * @param ScreenshotBroadcastRepository $repository
     * @param NotifierFactory $factory
     */
    public function __construct(
        ScreenshotBroadcastRepository $repository,
        NotifierFactory $factory
    ) {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    public function broadcast()
    {
        while ($screenshotBroadcast = $this->repository->getNext()) {
            $notifier = $this->factory->build($screenshotBroadcast);

            $notifier->notify($screenshotBroadcast);
        }
    }
}