<?php

namespace App\Service\Notifier;

use App\Entity\Screenshot;
use App\Entity\ScreenshotBroadcast;
use App\Repository\ScreenshotBroadcastRepository;
use TelegramBot\Api\BotApi;

class TelegramNotifier
{
    /**
     * @var BotApi
     */
    private $botApi;

    /**
     * @var string
     */
    private $chatId;

    /**
     * @var ScreenshotBroadcastRepository
     */
    private $repository;

    /**
     * @param BotApi $botApi
     * @param $chatId
     * @param ScreenshotBroadcastRepository $repository
     */
    public function __construct(BotApi $botApi, $chatId, ScreenshotBroadcastRepository $repository)
    {
        $this->botApi = $botApi;
        $this->chatId = $chatId;
        $this->repository = $repository;
    }

    /**
     * @return string
     */
    public function getChatId(): string
    {
        return $this->chatId;
    }

    /**
     * @param string $path
     * @return \CURLFile
     */
    public function getPhoto(string $path)
    {
        $photo = new \CURLFile('./screenshots/'.$path);
        return $photo;
    }

    public function notify(ScreenshotBroadcast $screenshotBroadcast)
    {
        $id = $screenshotBroadcast->attr('id');

        /** @var Screenshot $screenshot */
        $screenshot = $this->repository->getScreenshot($id);

        if (false === $screenshot) {
            return;
        }

        $caption = null;
        if ($shootedAt = $screenshot->shootedAt()) {
            $caption = $shootedAt->toRfc850String();
        }

        $photo = $this->getPhoto($screenshot->attr('path'));

        if ($message = $this->botApi->sendPhoto($this->chatId, $photo, $caption)) {
            $this->repository->markAsPublished($id);
        }
    }
}