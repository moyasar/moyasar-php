<?php

namespace Moyasar;

use Moyasar\Contracts\HttpClient;
use Moyasar\Exceptions\ApiException;
use Moyasar\Exceptions\ValidationException;
use Moyasar\Providers\InvoiceService;

class Invoice
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
    public $formattedAmount;

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

    /**
     * @var HttpClient
     */
    protected $client;

    private function __construct()
    {
    }

    /**
     * Creates an Invoice instance using provided data
     *
     * @param array $data
     * @param HttpClient $client
     * @return self
     */
    public static function fromArray($data, $client = null)
    {
        $invoice = new self();

        $invoice->client = $client;

        self::updateInstance($invoice, $data);

        return $invoice;
    }

    /**
     * @param self $invoice
     * @param array $data
     */
    private static function updateInstance($invoice, $data)
    {
        $invoice->id                = self::extract($data, 'id');
        $invoice->status            = self::extract($data, 'status');
        $invoice->amount            = self::extract($data, 'amount');
        $invoice->formattedAmount   = self::extract($data, 'amount_format');
        $invoice->currency          = self::extract($data, 'currency');
        $invoice->description       = self::extract($data, 'description');
        $invoice->expiredAt         = self::extract($data, 'expired_at');
        $invoice->logoUrl           = self::extract($data, 'logo_url');
        $invoice->url               = self::extract($data, 'url');
        $invoice->createdAt         = self::extract($data, 'created_at');
        $invoice->updatedAt         = self::extract($data, 'updated_at');
        $invoice->callbackUrl       = self::extract($data, 'callback_url');
        $invoice->paymentId         = self::extract($data, 'payment_id');
        $invoice->paidAt            = self::extract($data, 'paid_at');

        $payments                   = self::extract($data, 'payments', []);
        $paymentObjects = [];

        foreach ($payments as $payment) {
            $paymentObjects[] = Payment::fromArray($payment);
        }

        $invoice->payments = $paymentObjects;
    }

    /**
     * @param array $data
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    private static function extract($data, $key, $default = null)
    {
        return isset($data[$key]) ? $data[$key] : $default;
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
        $response = $this->client->put(InvoiceService::INVOICE_PATH . "/$this->id");
        self::updateInstance($this, $response['body_assoc']);
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
        self::updateInstance($this, $response['body_assoc']);
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