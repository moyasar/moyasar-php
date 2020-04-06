<?php

namespace Moyasar;

class CreditCard extends Source
{
    protected $type = 'creditcard';

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
     * Transaction URL
     *
     * @var string
     */
    public $transactionUrl;
}