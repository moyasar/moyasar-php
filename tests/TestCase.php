<?php

namespace Tests;

use Mockery;
use Moyasar\Providers\InvoiceService;
use Moyasar\Providers\HttpClient;
use Moyasar\Providers\PaymentService;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates an HttpClient mock that returns the same response
     *
     * @param int $status
     * @param string|null $responseFile
     * @return Mockery\LegacyMockInterface|Mockery\MockInterface|HttpClient
     */
    protected function mockHttpClient($status, $responseFile = null)
    {
        $double = Mockery::mock(HttpClient::class);

        $response = '';

        if ($responseFile) {
            $response = file_get_contents(__DIR__ . '/raw-responses/' . $responseFile);
            $response = $this->formatResponse($response, $status);
        }

        $double->shouldReceive('request')->andReturn($response);
        $double->shouldReceive('get')->andReturn($response);
        $double->shouldReceive('post')->andReturn($response);
        $double->shouldReceive('put')->andReturn($response);
        $double->shouldReceive('patch')->andReturn($response);
        $double->shouldReceive('delete')->andReturn($response);

        return $double;
    }

    /**
     * Creates an InvoiceService instance with mocked HttpClient
     *
     * @param int $status
     * @param string|null $responseFile
     * @return InvoiceService
     */
    protected function mockInvoiceService($status, $responseFile = null)
    {
        $clientDouble = $this->mockHttpClient($status, $responseFile);
        return new InvoiceService($clientDouble);
    }

    /**
     * Creates a PaymentService instance with mocked HttpClient
     *
     * @param int $status
     * @param string|null $responseFile
     * @return PaymentService
     */
    protected function mockPaymentService($status, $responseFile = null)
    {
        $clientDouble = $this->mockHttpClient($status, $responseFile);
        return new PaymentService($clientDouble);
    }

    /**
     * Formats the response of HttpClient, just the way you like it, Simple!
     *
     * @param string $response
     * @param int $status
     * @return array
     */
    private function formatResponse($response, $status)
    {
        return [
            'status' => $status,
            'headers' => [],
            'body' => $response,
            'body_assoc' => json_decode($response, true)
        ];
    }
}