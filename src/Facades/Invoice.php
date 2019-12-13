<?php

namespace Moyasar\Facades;

use Illuminate\Support\Facades\Facade;
use Moyasar\Providers\InvoiceService;

class Invoice extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return InvoiceService::class;
    }
}