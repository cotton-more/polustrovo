<?php

namespace App\Service\ScreenshotStorage;

use App\Repository\ScreenshotRepository;
use App\Service\Browshot\Response\ScreenshotResponse;
use Carbon\Carbon;
use Projek\Slim\Monolog;
use Ramsey\Uuid\UuidFactoryInterface;

class DoctrineStorage implements StorageInterface
{
    /**
     * @var ScreenshotRepository
     */
    private $repository;

    /**
     * @var UuidFactoryInterface
     */
    private $uuidFactory;

    /**
     * @var Monolog
     */
    private $logger;

    /**
     * DoctrineStorage constructor.
     * @param ScreenshotRepository $repository
     * @param UuidFactoryInterface $uuidFactory
     * @param Monolog $logger
     */
    public function __construct(ScreenshotRepository $repository, UuidFactoryInterface $uuidFactory, Monolog $logger)
    {
        $this->repository = $repository;
        $this->uuidFactory = $uuidFactory;
        $this->logger = $logger;
    }

    public function getPriority(): int
    {
        return 900;
    }

    /**
     * Handle screenshot storing
     *
     * @param ScreenshotResponse $screenshotResponse
     * @return bool
     */
    public function store(ScreenshotResponse $screenshotResponse): bool
    {
        $this->logger->debug('store to database', $screenshotResponse->toArray());
        $now = Carbon::now();

        $error = $screenshotResponse->error() ?: null;

        $data = [
            'status'        => $screenshotResponse->status(),
            'browshot_id'   => $screenshotResponse->id(),
            'created_at'    => $now->toDateTimeString(),
            'path'          => $screenshotResponse->getFilename(),
            'error'         => $error,
        ];

        // requeue if status is finished but there is an error
        if ($screenshotResponse->isStatusFinished() && $error) {
            $this->logger->warning(
                'response finished with an error',
                $screenshotResponse->toArray()
            );
        }

        $screenshotId = $this->uuidFactory->uuid4()->toString();

        $data['screenshot_id'] = $screenshotId;

        if ($finishedAt = $screenshotResponse->finished()) {
            $data['shooted_at'] = $finishedAt->toDateTimeString();
        }

        $this->logger->debug('set data', $data);

        $result = $this->repository->insert($data);

        if ($result) {
            $screenshotResponse->setScreenshotId($screenshotId);
        }

        $this->logger->debug('end', [
            'result' => $result,
        ]);

        return $result > 0;
    }
}