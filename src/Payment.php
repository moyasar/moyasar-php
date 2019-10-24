<?php


namespace Moyasar;


use Moyasar\Providers\HttpClient;
use Moyasar\Exceptions\ValidationException;
use Moyasar\Providers\PaymentService;

class Payment
{
    public $id;
    public $status;
    public $amount;
    public $fee;
    public $refundedAmount;
    public $refundedAt;
    public $formattedAmount;
    public $formattedFee;
    public $formattedRefundedAmount;
    public $currency;
    public $invoiceId;
    public $ip;
    public $callbackUrl;
    public $createdAt;
    public $updatedAt;
    public $source;
    public $description;
    public $captured;
    public $formattedCapturedAmount;
    public $capturedAt;
    public $voidedAt;

    /**
     * @var HttpClient
     */
    protected $client;

    private function __construct()
    {
    }

    /**
     * Creates a Payment instance using provided data
     *
     * @param array $data
     * @param HttpClient $client
     * @return Payment
     */
    public static function fromArray($data, $client = null)
    {
        $payment = new self();

        $payment->client = $client;

        self::updateInstance($payment, $data);

        return $payment;
    }

    /**
     * @param self $payment
     * @param array $data
     */
    private static function updateInstance($payment, $data)
    {
        $payment->id                        = self::extract($data, 'id');
        $payment->status                    = self::extract($data, 'status');
        $payment->amount                    = self::extract($data, 'amount');
        $payment->fee                       = self::extract($data, 'fee');
        $payment->refundedAmount            = self::extract($data, 'refunded');
        $payment->refundedAt                = self::extract($data, 'refunded_at');
        $payment->formattedAmount           = self::extract($data, 'amount_format');
        $payment->formattedFee              = self::extract($data, 'fee_format');
        $payment->formattedRefundedAmount   = self::extract($data, 'refunded_format');
        $payment->currency                  = self::extract($data, 'currency');
        $payment->invoiceId                 = self::extract($data, 'invoice_id');
        $payment->ip                        = self::extract($data, 'ip');
        $payment->callbackUrl               = self::extract($data, 'callback_url');
        $payment->createdAt                 = self::extract($data, 'created_at');
        $payment->updatedAt                 = self::extract($data, 'updated_at');
        $payment->description               = self::extract($data, 'description');
        $payment->captured                  = self::extract($data, 'captured');
        $payment->formattedCapturedAmount   = self::extract($data, 'captured_format');
        $payment->capturedAt                = self::extract($data, 'captured_at');
        $payment->voidedAt                  = self::extract($data, 'voided_at');

        $payment->source                    = self::extract($data, 'source');
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
     * @param string $description
     * @throws Exceptions\ApiException
     * @throws ValidationException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update($description)
    {
        $this->validateDescription($description);
        $response = $this->client->put(PaymentService::PAYMENT_PATH . "/$this->id");
        self::updateInstance($this, $response['body_assoc']);
    }

    /**
     * Refund the current payment instance
     *
     * @param int $amount
     * @throws Exceptions\ApiException
     * @throws ValidationException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function refund($amount)
    {
        if ($amount <= 0) {
            throw new ValidationException('Refund arguments are invalid', [
                'amount' => ['Amount must be a positive integer']
            ]);
        }

        $response = $this->client->post(PaymentService::PAYMENT_PATH . "/$this->id/refund", [
            'amount' => $amount
        ]);
        self::updateInstance($this, $response['body_assoc']);
    }

    /**
     * Capture a given amount of the authorized payment instance
     *
     * @param int $amount
     * @throws Exceptions\ApiException
     * @throws ValidationException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function capture($amount)
    {
        if ($amount <= 0) {
            throw new ValidationException('Capture arguments are invalid', [
                'amount' => ['Amount must be a positive integer']
            ]);
        }

        $response = $this->client->post(PaymentService::PAYMENT_PATH . "/$this->id/capture", [
            'amount' => $amount
        ]);
        self::updateInstance($this, $response['body_assoc']);
    }

    /**
     * Void the current payment instance
     *
     * @throws Exceptions\ApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function void()
    {
        $response = $this->client->post(PaymentService::PAYMENT_PATH . "/$this->id/void");
        self::updateInstance($this, $response['body_assoc']);
    }

    private function validateDescription($description)
    {
        if (trim(strlen($description)) == 0) {
            throw new ValidationException('Payment description is required', [
                'description' => 'A description is required'
            ]);
        }
    }
}