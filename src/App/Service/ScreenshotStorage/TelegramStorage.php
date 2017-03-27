<?php

namespace App\Service\ScreenshotStorage;

use App\Repository\ScreenshotRepository;
use App\Repository\TelegramSendPhotoRepository;
use App\Service\Browshot\Response\ScreenshotResponse;
use App\Service\Telegram\TelegramService;

class TelegramStorage implements StorageInterface
{
    /**
     * @var TelegramService
     */
    private $telegramService;

    /**
     * @var TelegramSendPhotoRepository
     */
    private $repository;

    public function __construct(TelegramService $telegramService, TelegramSendPhotoRepository $repository)
    {
        $this->telegramService = $telegramService;
        $this->repository = $repository;
    }

    /**
     * Handle screenshot storing
     * @param string $key
     * @param ScreenshotResponse $response
     * @return bool
     */
    public function store(string $key, ScreenshotResponse $response): bool
    {
        $data = [
            'chat_id' => $this->telegramService->getChatId(),
            'path'    => $key,
        ];

        // add shooted time and path to a file
        if (ScreenshotResponse::STATUS_FINISHED === $response->get('status')) {
            $this->repository->getDb()->insert('telegram_send_photo', $data);

            return true;
        }

        return false;
    }
}