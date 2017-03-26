<?php

namespace App\Service\Telegram;

use App\Repository\ScreenshotRepository;
use App\Screenshot;
use TelegramBot\Api\BotApi;

class TelegramService
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
     * @var ScreenshotRepository
     */
    private $repository;

    /**
     * TelegramService constructor.
     * @param BotApi $botApi
     * @param $chatId
     * @param ScreenshotRepository $repository
     */
    public function __construct(BotApi $botApi, $chatId, ScreenshotRepository $repository)
    {
        $this->botApi = $botApi;
        $this->chatId = $chatId;
        $this->repository = $repository;
    }

    /**
     * @return \TelegramBot\Api\Types\Message
     */
    public function sendLatestScreenshot()
    {
        $screenshot = $this->repository->getLatest();

        $photo = $this->getPhoto($screenshot);

        return $this->botApi->sendPhoto($this->chatId, $photo);
    }

    /**
     * @param Screenshot $screenshot
     * @return \CURLFile
     */
    public function getPhoto(Screenshot $screenshot)
    {
        $photo = new \CURLFile('./screenshots/'.$screenshot->attr('path'));
        return $photo;
    }

    /**
     * @param Screenshot $screenshot
     * @return bool|\TelegramBot\Api\Types\Message
     */
    public function sendScreenshot(Screenshot $screenshot)
    {
        if ($path = $screenshot->attr('path')) {
            $photo = $this->getPhoto($screenshot);

            return $this->botApi->sendPhoto($this->chatId, $photo);
        }

        return false;
    }
}