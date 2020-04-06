<?php

namespace Moyasar\Providers;

use Moyasar\Contracts\HttpClient as ClientContract;
use Moyasar\PaginationResult;
use Moyasar\Payment;
use Moyasar\Search;

class PaymentService
{
    const PAYMENT_PATH = 'payments';

    /**
     * @var ClientContract
     */
    protected $client;

    public function __construct($client = null)
    {
        if ($client == null) {
            $client = new HttpClient();
        }

        $this->client = $client;
    }

    /**
     * Fetches a payment from Moyasar servers
     *
     * @param string $id
     * @return Payment
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Moyasar\Exceptions\ApiException
     */
    public function fetch($id)
    {
        $response = $this->client->get(self::PAYMENT_PATH . "/$id");
        $payment = Payment::fromArray($response['body_assoc']);
        $payment->setClient($this->client);
        return $payment;
    }

    /**
     * Fetches all payments from Moyasar servers
     *
     * @param Search|array|null $query
     * @return PaginationResult
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Moyasar\Exceptions\ApiException
     */
    public function all($query = null)
    {
        if ($query instanceof Search) {
            $query = $query->toArray();
        }

        $response = $this->client->get(self::PAYMENT_PATH, $query);
        $data = $response['body_assoc'];
        $meta = $data['meta'];
        $payments = array_map(function ($i) {
            $payment = Payment::fromArray($i);
            $payment->setClient($this->client);
            return $payment;
        }, $data['payments']);

        return PaginationResult::fromArray($meta)->setResult($payments);
    }
}