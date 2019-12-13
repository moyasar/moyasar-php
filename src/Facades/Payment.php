<?php

namespace Moyasar\Facades;

use Illuminate\Support\Facades\Facade;
use Moyasar\Providers\PaymentService;

class Payment extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return PaymentService::class;
    }
}