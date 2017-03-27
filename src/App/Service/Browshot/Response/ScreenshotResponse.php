<?php

namespace App\Service\Browshot\Response;

use Carbon\Carbon;

class ScreenshotResponse
{
    const STATUS_IN_QUEUE   = 'in_queue';
    const STATUS_PROCESSING = 'processing';
    const STATUS_FINISHED   = 'finished';
    const STATUS_ERROR      = 'error';

    /**
     * @var mixed[]
     */
    private $container;

    /**
     * @var integer
     */
    private $code;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $screenshotId;

    /**
     * @param array $data
     * @param int $code
     */
    private function __construct(array $data, int $code)
    {
        $this->container = $data;
        $this->code = $code;
    }

    /**
     * @param  mixed[] $data
     * @param  int $code
     * @return ScreenshotResponse
     */
    public static function fromArray(array $data, int $code): ScreenshotResponse
    {
        return new static($data, $code);
    }

    /**
     * @return \mixed[]
     */
    public function toArray()
    {
        return $this->container;
    }

    /**
     * @return Carbon|null
     */
    public function finished()
    {
        if ($finished = $this->get('finished')) {
            $finished = Carbon::createFromTimestamp($finished / 1000);
        }

        return $finished;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($key)
    {
        return $this->container[$key] ?? null;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        $isOk = (self::STATUS_ERROR !== $this->get('status'));
        $error = $this->get('error');

        return $isOk && !$error;
    }

    public function isStatusFinished()
    {
        return self::STATUS_FINISHED === $this->get('status');
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return (string) $this->filename;
    }

    /**
     * @param string $filename
     * @return ScreenshotResponse
     */
    public function setFilename(string $filename): ScreenshotResponse
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return string
     */
    public function getScreenshotId(): string
    {
        return (string) $this->screenshotId;
    }

    /**
     * @param string $screenshotId
     * @return ScreenshotResponse
     */
    public function setScreenshotId(string $screenshotId): ScreenshotResponse
    {
        $this->screenshotId = $screenshotId;
        return $this;
    }

    /**
     * @param string $error
     * @param int|null $code
     * @return ScreenshotResponse
     */
    public function setError(string $error, int $code = null): ScreenshotResponse
    {
        $this->container['error'] = $error;

        if (null !== $code) {
            $this->container['code'] = $code;
        }

        return $this;
    }
}