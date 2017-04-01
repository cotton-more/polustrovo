<?php

namespace App\Service\Browshot\Response;

use Carbon\Carbon;

class ScreenshotResponse
{
    const STATUS_IN_QUEUE   = 'in_queue';
    const STATUS_PROCESSING = 'processing';
    const STATUS_FINISHED   = 'finished';
    const STATUS_ERROR      = 'error';

    const INSUFFICIENT_SIZE_CODE = 0x1000;

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
            $finished = Carbon::createFromTimestamp(mb_substr($finished, 0, 10));
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
    public function getFilename()
    {
        return $this->filename;
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
    public function getScreenshotId()
    {
        return $this->screenshotId;
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
     * @return string|null
     */
    public function status()
    {
        return $this->get('status');
    }

    /**
     * @return string|null
     */
    public function id()
    {
        return $this->get('id');
    }

    /**
     * @return string|null
     */
    public function error()
    {
        return $this->get('error');
    }

    /**
     * @return string|null
     */
    public function screenshotUrl()
    {
        return $this->get('screenshot_url');
    }

    /**
     * @param string $error
     * @return ScreenshotResponse
     */
    public function setError(string $error): ScreenshotResponse
    {
        $this->container['error'] = $error;

        return $this;
    }

    /**
     * @param $code
     * @return ScreenshotResponse
     */
    public function setCode($code): ScreenshotResponse
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return int
     */
    public function code(): int
    {
        return $this->code;
    }

    /**
     * @param string $status
     * @return ScreenshotResponse
     */
    public function setStatus(string $status): ScreenshotResponse
    {
        $this->container['status'] = $status;

        return $this;
    }
}