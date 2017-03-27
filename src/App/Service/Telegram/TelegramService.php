<?php

namespace App\Service\Telegram;

use App\Entity\TelegramSendPhoto;
use App\Repository\TelegramSendPhotoRepository;
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
     * @var TelegramSendPhotoRepository
     */
    private $repository;

    /**
     * TelegramService constructor.
     * @param BotApi $botApi
     * @param $chatId
     * @param TelegramSendPhotoRepository $repository
     */
    public function __construct(BotApi $botApi, $chatId, TelegramSendPhotoRepository $repository)
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
     * @return \TelegramBot\Api\Types\Message
     */
    public function sendLatestScreenshot()
    {
        $screenshot = $this->repository->getLatest();

        $photo = $this->getPhoto($screenshot);

        return $this->botApi->sendPhoto($this->chatId, $photo);
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

    public function sendPhoto()
    {
        /** @var TelegramSendPhoto $row */
        $row = $this->repository->getNext();

        if (false === $row) {
            return;
        }

        $photo = $this->getPhoto($row->attr('path'));

        if ($message = $this->botApi->sendPhoto($this->chatId, $photo)) {
            $this->repository->markAsPublished($row->attr('id'));
        }
    }
}