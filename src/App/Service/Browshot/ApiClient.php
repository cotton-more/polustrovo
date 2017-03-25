<?php

namespace App\Service\Browshot;

use App\Service\Browshot\Response\ScreenshotResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
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
     * @param array|null $query
     * @return ScreenshotResponse
     */
    public function createScreenshot(string $url, array $query = null): ScreenshotResponse
    {
        $uri = uri_for('screenshot/create');

        $query['url'] = $url;
        $query += [
            'instance_id' => $this->configuration->getInstanceId(),
            'key'         => $this->configuration->getApiKey(),
            'cache'       => 0,
        ];

        $result = $this->callApi('GET', $uri, [
            'query' => $query,
        ]);

        $screenshotResponse = ScreenshotResponse::fromArray($result['data'], $result['code']);

        return $screenshotResponse;
    }

    /**
     * @param string $id
     * @return ScreenshotResponse
     */
    public function screenshotInfo(string $id)
    {
        $uri = uri_for('screenshot/info');

        $query = [
            'id' => $id,
        ];
        $query += [
            'key'     => $this->configuration->getApiKey(),
            'details' => 2,
        ];

        $result = $this->callApi('GET', $uri, [
            'query' => $query,
        ]);

        $screenshotResponse = ScreenshotResponse::fromArray($result['data'], $result['code']);

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
        } catch (ClientException $ex) {
            $response = $ex->getResponse();

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
        }

        return [
            'code' => $statusCode,
            'data' => \GuzzleHttp\json_decode($body, true),
        ];
    }
}