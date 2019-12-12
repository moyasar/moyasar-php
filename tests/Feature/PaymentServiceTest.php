<?php

namespace Tests\Feature;

use Moyasar\Contracts\HttpClient;
use Moyasar\CreditCard;
use Moyasar\Payment;
use Moyasar\Sadad;
use ReflectionClass;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    public function test_payment_is_fetched_correctly()
    {
        $service = $this->mockPaymentService(200, 'payment/payment.json');

        $payment = $service->fetch('ae5e8c6a-1622-45a5-b7ca-9ead69be722e');

        $this->assertTrue($payment instanceof Payment);

        $paymentData = $this->getSinglePaymentRaw();

        $this->assertPaymentDataValid($payment, $paymentData);
    }

    public function test_payment_instance_has_client_instance()
    {
        $service = $this->mockPaymentService(200, 'payment/payment.json');

        $payment = $service->fetch('915fc838-f2c6-46ec-be44-4a93c9500f5f');

        $this->assertTrue($payment instanceof Payment);

        $refClass = new ReflectionClass(Payment::class);
        $refProp = $refClass->getProperty('client');
        $refProp->setAccessible(true);

        $this->assertTrue($refProp->getValue($payment) instanceof HttpClient);
    }

    public function test_payments_are_listed_correctly()
    {
        $service = $this->mockPaymentService(200, 'payment/payment_list.json');

        $pgResult = $service->all();

        $payments = $pgResult->result;

        $pgResultData = $this->getAllPaymentsRaw();

        $meta = $pgResultData['meta'];

        $this->assertEquals($meta['current_page'], $pgResult->currentPage);
        $this->assertEquals($meta['next_page'], $pgResult->nextPage);
        $this->assertEquals($meta['prev_page'], $pgResult->previousPage);
        $this->assertEquals($meta['total_pages'], $pgResult->totalPages);
        $this->assertEquals($meta['total_count'], $pgResult->totalCount);

        $paymentsData = $pgResultData['payments'];

        $current = -1;

        foreach ($payments as $payment) {
            $this->assertTrue($payment instanceof Payment);
            $paymentData = $paymentsData[++$current];
            $this->assertPaymentDataValid($payment, $paymentData);
        }
    }

    protected function getSinglePaymentRaw()
    {
        return json_decode(file_get_contents(__DIR__ . '/../raw-responses/payment/payment.json'), true);
    }

    protected function getSingleUpdatedPaymentRaw()
    {
        return json_decode(file_get_contents(__DIR__ . '/../raw-responses/payment/payment_updated.json'), true);
    }

    protected function getAllPaymentsRaw()
    {
        return json_decode(file_get_contents(__DIR__ . '/../raw-responses/payment/payment_list.json'), true);
    }
    
    /**
     * Asserts that a Payment instance equals some raw data
     *
     * @param Payment $payment
     * @param array $paymentData
     */
    protected function assertPaymentDataValid($payment, $paymentData)
    {
        $this->assertTrue($payment instanceof Payment);

        $this->assertEquals($paymentData['id'], $payment->id);
        $this->assertEquals($paymentData['status'], $payment->status);
        $this->assertEquals($paymentData['amount'], $payment->amount);
        $this->assertEquals($paymentData['fee'], $payment->fee);
        $this->assertEquals($paymentData['currency'], $payment->currency);
        $this->assertEquals($paymentData['refunded'], $payment->refunded);
        $this->assertEquals($paymentData['refunded_at'], $payment->refundedAt);
        $this->assertEquals($paymentData['description'], $payment->description);
        $this->assertEquals($paymentData['amount_format'], $payment->amountFormat);
        $this->assertEquals($paymentData['fee_format'], $payment->feeFormat);
        $this->assertEquals($paymentData['refunded_format'], $payment->refundedFormat);
        $this->assertEquals($paymentData['invoice_id'], $payment->invoiceId);
        $this->assertEquals($paymentData['ip'], $payment->ip);
        $this->assertEquals($paymentData['callback_url'], $payment->callbackUrl);
        $this->assertEquals($paymentData['created_at'], $payment->createdAt);
        $this->assertEquals($paymentData['updated_at'], $payment->updatedAt);

        if (isset($paymentData['captured'])) {
            $this->assertEquals($paymentData['captured'], $payment->captured);
        }

        if (isset($paymentData['captured_at'])) {
            $this->assertEquals($paymentData['captured_at'], $payment->capturedAt);
        }

        if (isset($paymentData['captured_format'])) {
            $this->assertEquals($paymentData['captured_format'], $payment->capturedFormat);
        }

        if (isset($paymentData['voided_at'])) {
            $this->assertEquals($paymentData['voided_at'], $payment->voidedAt);
        }

        $sourceData = $paymentData['source'];

        if ($payment->source instanceof CreditCard) {
            $this->assertEquals('creditcard', $sourceData['type']);

            $this->assertEquals($sourceData['company'], $payment->source->company);
            $this->assertEquals($sourceData['name'], $payment->source->name);
            $this->assertEquals($sourceData['number'], $payment->source->number);
            $this->assertEquals($sourceData['message'], $payment->source->message);
            $this->assertEquals($sourceData['transaction_url'], $payment->source->transactionUrl);
        }

        if ($payment->source instanceof Sadad) {
            $this->assertEquals('sadad', $sourceData['type']);

            $this->assertEquals($sourceData['username'], $payment->source->username);
            $this->assertEquals($sourceData['error_code'], $payment->source->errorCode);
            $this->assertEquals($sourceData['message'], $payment->source->message);
            $this->assertEquals($sourceData['transaction_id'], $payment->source->transactionId);
            $this->assertEquals($sourceData['transaction_url'], $payment->source->transactionUrl);
        }
    }
}