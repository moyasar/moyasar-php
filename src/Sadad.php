<?php

namespace Moyasar;

class Sadad extends Source
{
    protected $type = 'sadad';

    /**
     * Client username
     *
     * @var string
     */
    public $username;

    /**
     * Transaction error code if any
     *
     * @var mixed
     */
    public $errorCode;

    /**
     * Transaction message
     *
     * @var string
     */
    public $message;

    /**
     * Transaction ID
     *
     * @var mixed
     */
    public $transactionId;

    /**
     * Transaction URL
     *
     * @var string
     */
    public $transactionUrl;
}