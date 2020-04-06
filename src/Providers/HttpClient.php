<?php

namespace Moyasar\Providers;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Moyasar\Contracts\HttpClient as HttpClientContract;
use Moyasar\Exceptions\ApiException;
use Moyasar\Moyasar;
use Psr\Http\Message\ResponseInterface;

class HttpClient implements HttpClientContract
{
    /**
     * @var ClientInterface
     */
    protected $httpClient;

    public function __construct(ClientInterface $httpClient = null)
    {
        if ($httpClient == null) {
            $httpClient = (new GuzzleClientFactory())->build();
        }

        $this->httpClient = $httpClient;
    }

    /**
     * @param $method string
     * @param $url string
     * @param $data array
     * @return array
     * @throws ApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($method, $url, $data = null)
    {
        $options = $this->clientOptions();
        $method = strtolower($method);

        if ($method == 'get') {
            $options[RequestOptions::QUERY] = $data;
        } else {
            $options[RequestOptions::JSON] = $data;
        }

        try {
            $response = $this->httpClient->request($method, $url, $options);
            return $this->formatResponse($response);
        } catch (ClientException $exception) {
            $response = $exception->getResponse();

            if ($response->getStatusCode() >= 400 && $response->getStatusCode() < 600) {
                $responseBody = json_decode($response->getBody()->getContents(), true);

                $message = isset($responseBody['message']) ? $responseBody['message'] : '';
                $type = isset($responseBody['type']) ? $responseBody['type'] : '';
                $errors = isset($responseBody['errors']) ? $responseBody['errors'] : [];

                throw new ApiException($message, $type, $errors, $response->getStatusCode(), $exception);
            }

            throw $exception;
        }
    }

    /**
     * @param $url string
     * @param $data array
     * @return array
     * @throws ApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($url, $data = null)
    {
        return $this->request('get', $url, $data);
    }

    /**
     * @param $url string
     * @param $data array
     * @return array
     * @throws ApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post($url, $data = null)
    {
        return $this->request('post', $url, $data);
    }

    /**
     * @param $url string
     * @param $data array
     * @return array
     * @throws ApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function put($url, $data = null)
    {
        return $this->request('put', $url, $data);
    }

    /**
     * @param $url string
     * @param $data array
     * @return array
     * @throws ApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function patch($url, $data = null)
    {
        return $this->request('patch', $url, $data);
    }

    /**
     * @param $url string
     * @param $data array
     * @return array
     * @throws ApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete($url, $data = null)
    {
        return $this->request('delete', $url, $data);
    }

    private function clientOptions()
    {
        return [
            RequestOptions::AUTH => [Moyasar::getApiKey(), '']
        ];
    }

    private function formatResponse(ResponseInterface $response)
    {
        $body = $response->getBody()->getContents();

        return [
            'status' => $response->getStatusCode(),
            'headers' => $response->getHeaders(),
            'body' => $body,
            'body_assoc' => json_decode($body, true)
        ];
    }
}