<?php

namespace App\Service\Notifier;

use App\Entity\Screenshot;
use App\Entity\ScreenshotBroadcast;
use App\Repository\ScreenshotBroadcastRepository;
use Pushbullet\Exceptions\PushbulletException;
use Pushbullet\Pushbullet;

class PushbulletNotifier
{
    /**
     * @var Pushbullet
     */
    private $pushbullet;

    /**
     * @var string
     */
    private $channel;

    /**
     * @var ScreenshotBroadcastRepository
     */
    private $repository;

    /**
     * PushbulletNotifier constructor.
     * @param Pushbullet $pushbullet
     * @param string $channel
     * @param ScreenshotBroadcastRepository $repository
     */
    public function __construct(
        Pushbullet $pushbullet,
        $channel,
        ScreenshotBroadcastRepository $repository
    ) {
        $this->pushbullet = $pushbullet;
        $this->channel = $channel;
        $this->repository = $repository;
    }

    /**
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * @param string $path
     * @return \CURLFile
     */
    public function getPhoto(string $path)
    {
        return realpath('./screenshots/'.$path);
    }

    public function notify(ScreenshotBroadcast $screenshotBroadcast)
    {
        $id = $screenshotBroadcast->attr('id');

        /** @var Screenshot $screenshot */
        $screenshot = $this->repository->getScreenshot($id);

        if (false === $screenshot) {
            return;
        }

        $photo = $this->getPhoto($screenshot->attr('path'));

        $channel = $this->pushbullet->channel($this->channel);

        try {
            $channel->pushFile($photo);
            $this->repository->markAsPublished($id);
        } catch (PushbulletException $exception) {
            // todo: add logger
            dump($exception); die;
        }
    }
}