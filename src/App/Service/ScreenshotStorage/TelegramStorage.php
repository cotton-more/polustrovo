<?php

namespace App\Service\ScreenshotStorage;

use App\Repository\ScreenshotRepository;
use App\Repository\TelegramSendPhotoRepository;
use App\Service\Browshot\Response\ScreenshotResponse;
use App\Service\Telegram\TelegramService;
use Projek\Slim\Monolog;

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

    /**
     * @var Monolog
     */
    private $logger;

    /**
     * TelegramStorage constructor.
     * @param TelegramService $telegramService
     * @param TelegramSendPhotoRepository $repository
     * @param Monolog $logger
     */
    public function __construct(
        TelegramService $telegramService,
        TelegramSendPhotoRepository $repository,
        Monolog $logger
    ) {
        $this->telegramService = $telegramService;
        $this->repository = $repository;
        $this->logger = $logger;
    }

    public function getPriority(): int
    {
        return 10;
    }

    /**
     * Handle screenshot storing
     * @param ScreenshotResponse $response
     * @return bool
     */
    public function store(ScreenshotResponse $response): bool
    {
        $this->logger->debug('store to telegram');

        $data = [
            'chat_id'       => $this->telegramService->getChatId(),
            'path'          => $response->getFilename(),
            'screenshot_id' => $response->getScreenshotId() ?: null,
        ];

        $this->logger->debug('data', $data);

        $result = null;
        if ($response->isStatusFinished() && $response->isSuccess()) {
            $result = $this->repository->getDb()->insert('telegram_send_photo', $data);
        } else {
            $this->logger->debug('invalid response', [
                'status' => $response->status(),
                'code' => $response->code(),
                'error' => $response->error(),
            ]);
        }

        $this->logger->debug('end', [
            'result' => $result,
        ]);

        return $result > 0;
    }
}