<?php

namespace Moyasar;

use Moyasar\Contracts\HttpClient;

class OnlineResource extends Resource
{
    protected $skipProps = [
        'client'
    ];

    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * Set instance HttpClient
     *
     * @param HttpClient $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }
}