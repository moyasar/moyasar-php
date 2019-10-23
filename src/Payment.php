<?php


namespace Moyasar;


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

    protected $client;

    public function update()
    {
        
    }

    public function refund()
    {
        
    }

    private function __construct()
    {
    }

    public static function fromArray($data, $client = null)
    {
        $payment = new self();

        $payment->client = $client;

        self::updateInstance($payment, $data);

        return $payment;
    }

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

    private static function extract($data, $key, $default = null)
    {
        return isset($data[$key]) ? $data[$key] : $default;
    }
}