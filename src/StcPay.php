<?php

namespace Moyasar;

class StcPay extends Source
{
    protected $type = 'stcpay';

    /**
     * Customer Mobile Number
     *
     * @var string
     */
    public $mobile;

    /**
     * Reference Number
     *
     * @var string
     */
    public $referenceNumber;

    /**
     * Branch
     *
     * @var string
     */
    public $branch;

    /**
     * Cashier
     *
     * @var string
     */
    public $cashier;

    /**
     * Transaction URL
     *
     * @var string
     */
    public $transactionUrl;

    /**
     * Transaction Message
     *
     * @var string
     */
    public $message;
}
