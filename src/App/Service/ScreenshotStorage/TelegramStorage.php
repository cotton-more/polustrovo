<?php

namespace App\Service\ScreenshotStorage;

use App\Repository\ScreenshotRepository;
use App\Service\Browshot\Response\ScreenshotResponse;
use App\Service\Telegram\TelegramService;

class TelegramStorage implements StorageInterface
{
    /**
     * @var TelegramService
     */
    private $telegramService;

    /**
     * @var ScreenshotRepository
     */
    private $screenshotRepository;

    public function __construct(TelegramService $telegramService, ScreenshotRepository $screenshotRepository)
    {
        $this->telegramService = $telegramService;
        $this->screenshotRepository = $screenshotRepository;
    }

    /**
     * Handle screenshot storing
     * @param string $key
     * @param ScreenshotResponse $response
     * @return bool
     */
    public function store(string $key, ScreenshotResponse $response): bool
    {
        dump($response);
        if (ScreenshotResponse::STATUS_FINISHED === $response->get('status')) {
            $browshotId = $response->get('id');
            $screenshot = $this->screenshotRepository->findByBrowshotId($browshotId);

            $this->telegramService->sendScreenshot($screenshot);
        }

        return false;
    }
}