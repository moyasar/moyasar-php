<?php

namespace Moyasar\Facades;

use Illuminate\Support\Facades\Facade;
use Moyasar\Providers\PaymentService;

/**
 * @methid static \Moyasar\Payment fetch($id)
 * @methid static \Moyasar\PaginationResult all($query = null)
 *
 * @see \Moyasar\Providers\PaymentService
 *
 * Class Payment
 * @package Moyasar\Facades
 */
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