<?php

namespace Moyasar;

class ApplePay extends Source
{
    protected $type = 'applepay';

    /**
     * @var string
     */
    public $company;

    /**
     * Card holder name
     *
     * @var string
     */
    public $name;

    /**
     * Credit card number
     *
     * @var string
     */
    public $number;

    /**
     * Transaction message
     *
     * @var string
     */
    public $message;

    /**
     * Gateway ID
     *
     * @var string
     */
    public $gatewayId;

    /**
     * Gateway ID
     *
     * @var string
     */
    public $referenceNumber;
}
