<?php


namespace Moyasar\Providers;


use Moyasar\Moyasar;

class PaymentService
{
    /**
     * @var Moyasar
     */
    protected $moyasar;

    public function __construct($moyasar = null)
    {
        $this->moyasar = $moyasar;
    }

    public function fetch()
    {
        
    }

    public function list()
    {
        
    }
}