<?php

namespace Moyasar\Providers;

use Moyasar\Contracts\HttpClient as ClientContract;
use Moyasar\PaginationResult;
use Moyasar\Payment;
use Moyasar\Search;

class PaymentService
{
    const PAYMENT_PATH = '/payment';

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
        return Payment::fromArray($response['body_assoc'], $this->client);
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
        $payments = array_map(function ($i) { return Payment::fromArray($i, $this->client); }, $data['payments']);

        return PaginationResult::fromArray($meta, $payments);
    }
}