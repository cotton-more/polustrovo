<?php
/**
 * Created by PhpStorm.
 * User: inikulin
 * Date: 19.04.17
 * Time: 22:28
 */

namespace App\Service\Notifier;

use App\Entity\PushbulletChannelPush;
use App\Entity\Screenshot;
use App\Repository\PushbulletChannelPushRepository;
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
     * @var PushbulletChannelPushRepository
     */
    private $repository;

    /**
     * PushbulletNotifier constructor.
     * @param Pushbullet $pushbullet
     * @param $channel
     * @param PushbulletChannelPushRepository $repository
     */
    public function __construct(
        Pushbullet $pushbullet,
        $channel,
        PushbulletChannelPushRepository $repository
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

    public function sendPhoto()
    {
        /** @var PushbulletChannelPush $row */
        $row = $this->repository->getNext();

        if (false === $row) {
            return;
        }

        /** @var Screenshot $screenshot */
        $screenshot = $this->repository->getScreenshot($row->attr('screenshot_id'));

        if (false === $screenshot) {
            return;
        }

        $photo = $this->getPhoto($screenshot->attr('path'));

        $channel = $this->pushbullet->channel($this->channel);

        try {
            $channel->pushFile($photo);
            $this->repository->markAsPublished($row->attr('id'));
        } catch (PushbulletException $exception) {
            // todo: add logger
            dump($exception); die;
        }
    }
}