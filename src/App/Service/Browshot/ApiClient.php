<?php

namespace App\Service\Browshot;

use App\Service\Browshot\Model\ScreenshotSimple;
use App\Service\Browshot\Response\ScreenshotErrorResponse;
use App\Service\Browshot\Response\ScreenshotResponse;
use App\Service\Browshot\Response\ScreenshotSuccessResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use function GuzzleHttp\Psr7\uri_for;
use Psr\Http\Message\UriInterface;

class ApiClient
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var Client
     */
    private $client;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;

        $this->client = new Client([
            'base_uri' => $configuration->getHost(),
        ]);
    }

    /**
     * @param string $url
     * @param int|null $cache
     * @return ScreenshotResponse
     */
    public function createScreenshot(string $url, int $cache = null): ScreenshotResponse
    {
        $uri = uri_for('screenshot/create');

        $query = [
            'instance_id' => $this->configuration->getInstanceId(),
            'key' => $this->configuration->getApiKey(),
            'url' => $url,
        ];

        if (null !== $cache) {
            $query['cache'] = $cache;
        }

        $result = $this->callApi('GET', $uri, [
            'query' => $query,
        ]);

        if (200 === $result['code']) {
            $screenshotResponse = ScreenshotSuccessResponse::fromArray($result['data']);
        } else {
            $screenshotResponse = ScreenshotErrorResponse::fromArray($result['data']);
        }

        return $screenshotResponse;
    }

    /**
     * @param string $method
     * @param UriInterface $uri
     * @param array $options
     * @return array
     * @throws ScreenshotException
     */
    public function callApi(string $method, UriInterface $uri, array $options)
    {
        try {
            $response = $this->client->request($method, $uri, $options);

            $statusCode = $response->getStatusCode();

            $body = $response->getBody()->getContents();

            return [
                'code' => $statusCode,
                'data' => \GuzzleHttp\json_decode($body, true),
            ];
        } catch (GuzzleException $ex) {
            $errorMessage = $ex->getMessage();
        }

        throw new ScreenshotException($errorMessage);
    }
}