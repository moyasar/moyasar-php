<?php


namespace Tests;


use Mockery;
use Moyasar\Providers\InvoiceService;
use Moyasar\Providers\HttpClient;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
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

    protected function mockInvoiceService($status, $responseFile = null)
    {
        $clientDouble = $this->mockHttpClient($status, $responseFile);
        return new InvoiceService($clientDouble);
    }

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