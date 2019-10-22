<?php

namespace Moyasar\Providers;

use GuzzleHttp\Client;
use Moyasar\Moyasar;

class GuzzleClientFactory
{
    public function build()
    {
        return new Client($this->options());
    }

    public function options()
    {
        return [
            'base_uri' => Moyasar::CURRENT_VERSION_URL,
            'auth' => [Moyasar::getApiKey(), '']
        ];
    }
}