<?php

namespace Moyasar;

use Moyasar\Exceptions\ValidationException;
use Moyasar\Providers\InvoiceService;

class Invoice extends OnlineResource
{
    /**
     * Invoice Id
     *
     * @var string
     */
    public $id;

    /**
     * Invoice status
     *
     * @var string
     */
    public $status;

    /**
     * Invoice amount
     *
     * @var int
     */
    public $amount;

    /**
     * Formatted invoice amount
     *
     * @var string
     */
    public $amountFormat;

    /**
     * Invoice currency
     *
     * @var string
     */
    public $currency;

    /**
     * Invoice description
     *
     * @var string
     */
    public $description;

    /**
     * Invoice expiration date
     *
     * @var string
     */
    public $expiredAt;

    /**
     * Invoice Logo URL
     *
     * @var string
     */
    public $logoUrl;

    /**
     * Invoice URL
     *
     * @var string
     */
    public $url;

    /**
     * Invoice creation date
     *
     * @var string
     */
    public $createdAt;

    /**
     * Last time invoice was updated
     *
     * @var string
     */
    public $updatedAt;

    /**
     * Invoice payment objects
     *
     * @var Payment[]
     */
    public $payments = [];

    /**
     * Invoice callback URL
     *
     * @var string
     */
    public $callbackUrl;

    /**
     * Payment ID that paid this invoice
     *
     * @var string
     */
    public $paymentId;

    /**
     * Date and Time invoice was paid
     *
     * @var string
     */
    public $paidAt;

    protected function __construct()
    {
    }

    protected static function transform($key, $value)
    {
        if ($key == 'payments') {
            return array_map([Payment::class, 'fromArray'], $value);
        }

        return $value;
    }

    protected static function transformBack($key, $value)
    {
        if ($key == 'payments') {
            return array_map(function ($p) {
                return $p->toSnakeArray();
            }, $value);
        }

        return $value;
    }

    /**
     * Update the current invoice instance
     *
     * @param array $arguments
     * @return void
     * @throws ValidationException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Moyasar\Exceptions\ApiException
     */
    public function update($arguments)
    {
        $this->validateUpdateArguments($arguments);
        $response = $this->client->put(InvoiceService::INVOICE_PATH . "/$this->id", $arguments);
        $this->updateFromArray($response['body_assoc']);
    }

    /**
     * Cancels this invoice instance
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Moyasar\Exceptions\ApiException
     */
    public function cancel()
    {
        $response = $this->client->put(InvoiceService::INVOICE_PATH . "/$this->id/cancel");
        $this->updateFromArray($response['body_assoc']);
    }

    /**
     * Validates arguments meant to be used with invoice create
     *
     * @param $arguments
     * @throws ValidationException
     */
    private function validateUpdateArguments($arguments)
    {
        $errors = [];

        if (isset($arguments['amount']) && (!is_int($arguments['amount']) || $arguments['amount'] <= 0)) {
            $errors['amount'][] = 'Amount must be a positive integer greater than 0';
        }

        if (isset($arguments['currency']) && strlen($arguments['currency']) != 3) {
            $errors['currency'][] = 'Currency must be a 3-letter currency ISO code';
        }

        if (isset($arguments['description']) && strlen(trim($arguments['description'])) == 0) {
            $errors['description'][] = 'A description is required';
        }

        if (count($errors)) {
            throw new ValidationException('Invoice arguments are invalid', $errors);
        }
    }
}