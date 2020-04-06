<?php

namespace Moyasar\Facades;

use Illuminate\Support\Facades\Facade;
use Moyasar\Providers\InvoiceService;

/**
 * @method static \Moyasar\Invoice create($arguments)
 * @method static \Moyasar\Invoice fetch($id)
 * @method static \Moyasar\PaginationResult all($query = null)
 *
 * @see \Moyasar\Providers\InvoiceService
 *
 * Class Invoice
 * @package Moyasar\Facades
 */
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