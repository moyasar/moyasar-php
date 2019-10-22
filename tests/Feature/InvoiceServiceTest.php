<?php

namespace Tests\Feature;

use Moyasar\Exceptions\ValidationException;
use Moyasar\Invoice;
use Moyasar\Payment;
use ReflectionClass;
use Tests\TestCase;

class InvoiceServiceTest extends TestCase
{
    public function test_new_invoice_is_created()
    {
        $service = $this->mockInvoiceService(201, 'invoice/invoice.json');

        $invoice = $service->create([
            'amount' => 300,
            'currency' => 'SAR',
            'description' => 'Test Invoice'
        ]);

        $this->assertTrue($invoice instanceof Invoice);

        $invoiceData = $this->getSingleInvoiceRaw();

        $this->assertInvoiceDataValid($invoice, $invoiceData);
    }

    public function test_invoice_is_fetched_correctly()
    {
        $service = $this->mockInvoiceService(200, 'invoice/invoice.json');

        $invoice = $service->fetch('915fc838-f2c6-46ec-be44-4a93c9500f5f');

        $this->assertTrue($invoice instanceof Invoice);

        $invoiceData = $this->getSingleInvoiceRaw();

        $this->assertInvoiceDataValid($invoice, $invoiceData);
    }

    public function test_invoices_are_listed_correctly()
    {
        $service = $this->mockInvoiceService(200, 'invoice/invoice_list.json');

        $pgResult = $service->all();

        $invoices = $pgResult->result;

        $pgResultData = $this->getAllInvoicesRaw();

        $meta = $pgResultData['meta'];

        $this->assertEquals($meta['current_page'], $pgResult->currentPage);
        $this->assertEquals($meta['next_page'], $pgResult->nextPage);
        $this->assertEquals($meta['prev_page'], $pgResult->previousPage);
        $this->assertEquals($meta['total_pages'], $pgResult->totalPages);
        $this->assertEquals($meta['total_count'], $pgResult->totalCount);

        $invoicesData = $pgResultData['invoices'];

        $current = -1;

        foreach ($invoices as $invoice) {
            $this->assertTrue($invoice instanceof Invoice);
            $invoiceData = $invoicesData[++$current];
            $this->assertInvoiceDataValid($invoice, $invoiceData);
        }
    }

    public function test_invoice_is_canceled_correctly()
    {
        $service = $this->mockInvoiceService(200, 'invoice/invoice.json');

        $invoice = $service->fetch('915fc838-f2c6-46ec-be44-4a93c9500f5f');

        $this->assertTrue($invoice instanceof Invoice);

        $client = $this->mockHttpClient(200, 'invoice/invoice_canceled.json');

        $reflectionClass = new ReflectionClass(Invoice::class);
        $reflectionProperty = $reflectionClass->getProperty('client');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($invoice, $client);

        $invoice->cancel();

        $invoiceData = $this->getSingleCanceledInvoiceRaw();

        $this->assertInvoiceDataValid($invoice, $invoiceData);
    }

    public function test_invoice_is_updated_correctly()
    {
        $service = $this->mockInvoiceService(200, 'invoice/invoice.json');

        $invoice = $service->fetch('915fc838-f2c6-46ec-be44-4a93c9500f5f');

        $this->assertTrue($invoice instanceof Invoice);

        $client = $this->mockHttpClient(200, 'invoice/invoice_updated.json');

        $reflectionClass = new ReflectionClass(Invoice::class);
        $reflectionProperty = $reflectionClass->getProperty('client');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($invoice, $client);

        $invoice->update([
            'amount' => 10000
        ]);

        $invoiceData = $this->getSingleUpdatedInvoiceRaw();

        $this->assertInvoiceDataValid($invoice, $invoiceData);
    }

    public function test_validation_exception_is_thrown_when_amount_is_not_provided()
    {
        $service = $this->mockInvoiceService(201, 'invoice/invoice.json');

        $this->expectException(ValidationException::class);

        $invoice = $service->create([
            'currency' => 'SAR',
            'description' => 'Test Invoice'
        ]);
    }

    public function test_validation_exception_is_thrown_when_description_is_not_provided()
    {
        $service = $this->mockInvoiceService(201, 'invoice/invoice.json');

        $this->expectException(ValidationException::class);

        $invoice = $service->create([
            'amount' => 300,
            'currency' => 'SAR'
        ]);
    }

    public function test_validation_exception_is_thrown_when_amount_is_not_correct()
    {
        $service = $this->mockInvoiceService(201, 'invoice/invoice.json');

        $this->expectException(ValidationException::class);

        $invoice = $service->create([
            'amount' => -1,
            'currency' => 'SAR',
            'description' => 'Test Invoice'
        ]);
    }

    public function test_validation_exception_is_thrown_when_currency_is_not_correct()
    {
        $service = $this->mockInvoiceService(201, 'invoice/invoice.json');

        $this->expectException(ValidationException::class);

        $invoice = $service->create([
            'amount' => 300,
            'currency' => 'hello',
            'description' => 'Test Invoice'
        ]);
    }

    public function test_validation_exception_is_thrown_when_description_is_not_correct()
    {
        $service = $this->mockInvoiceService(201, 'invoice/invoice.json');

        $this->expectException(ValidationException::class);

        $invoice = $service->create([
            'amount' => 300,
            'currency' => 'SAR',
            'description' => ' '
        ]);
    }

    public function test_invoice_update_validation_exception_is_thrown_when_amount_is_not_correct()
    {
        $service = $this->mockInvoiceService(200, 'invoice/invoice.json');

        $invoice = $service->fetch('915fc838-f2c6-46ec-be44-4a93c9500f5f');

        $this->expectException(ValidationException::class);

        $invoice->update([
            'amount' => -1
        ]);
    }

    public function test_invoice_update_validation_exception_is_thrown_when_currency_is_not_correct()
    {
        $service = $this->mockInvoiceService(200, 'invoice/invoice.json');

        $invoice = $service->fetch('915fc838-f2c6-46ec-be44-4a93c9500f5f');

        $this->expectException(ValidationException::class);

        $invoice->update([
            'currency' => 'hello'
        ]);
    }

    public function test_invoice_update_validation_exception_is_thrown_when_description_is_not_correct()
    {
        $service = $this->mockInvoiceService(200, 'invoice/invoice.json');

        $invoice = $service->fetch('915fc838-f2c6-46ec-be44-4a93c9500f5f');

        $this->expectException(ValidationException::class);

        $invoice->update([
            'description' => ' '
        ]);
    }

    protected function getSingleInvoiceRaw()
    {
        return json_decode(file_get_contents(__DIR__ . '/../raw-responses/invoice/invoice.json'), true);
    }

    protected function getSingleCanceledInvoiceRaw()
    {
        return json_decode(file_get_contents(__DIR__ . '/../raw-responses/invoice/invoice_canceled.json'), true);
    }

    protected function getSingleUpdatedInvoiceRaw()
    {
        return json_decode(file_get_contents(__DIR__ . '/../raw-responses/invoice/invoice_updated.json'), true);
    }

    protected function getAllInvoicesRaw()
    {
        return json_decode(file_get_contents(__DIR__ . '/../raw-responses/invoice/invoice_list.json'), true);
    }

    /**
     * @param Invoice $invoice
     * @param array $invoiceData
     */
    protected function assertInvoiceDataValid($invoice, $invoiceData)
    {
        $this->assertEquals($invoiceData['id'], $invoice->id);
        $this->assertEquals($invoiceData['status'], $invoice->status);
        $this->assertEquals($invoiceData['amount'], $invoice->amount);
        $this->assertEquals($invoiceData['currency'], $invoice->currency);
        $this->assertEquals($invoiceData['description'], $invoice->description);
        $this->assertEquals($invoiceData['amount_format'], $invoice->formattedAmount);
        $this->assertEquals($invoiceData['url'], $invoice->url);
        $this->assertEquals($invoiceData['created_at'], $invoice->createdAt);
        $this->assertEquals($invoiceData['updated_at'], $invoice->updatedAt);
        $this->assertEquals($invoiceData['expired_at'], $invoice->expiredAt);
        $this->assertEquals($invoiceData['callback_url'], $invoice->callbackUrl);

        $this->assertTrue(is_array($invoice->payments));

        if (isset($invoiceData['payments'])) {
            $this->assertTrue(count($invoice->payments) > 0);

            $payment = $invoice->payments[0];
            $paymentData = $invoiceData['payments'][0];

            $this->assertTrue($payment instanceof Payment);

            $this->assertEquals($paymentData['id'], $payment->id);
            $this->assertEquals($paymentData['status'], $payment->status);
            $this->assertEquals($paymentData['amount'], $payment->amount);
            $this->assertEquals($paymentData['fee'], $payment->fee);
            $this->assertEquals($paymentData['currency'], $payment->currency);
            $this->assertEquals($paymentData['refunded'], $payment->refundedAmount);
            $this->assertEquals($paymentData['refunded_at'], $payment->refundedAt);
            $this->assertEquals($paymentData['description'], $payment->description);
            $this->assertEquals($paymentData['amount_format'], $payment->formattedAmount);
            $this->assertEquals($paymentData['fee_format'], $payment->formattedFee);
            $this->assertEquals($paymentData['refunded_format'], $payment->formattedRefundedAmount);
            $this->assertEquals($paymentData['invoice_id'], $payment->invoiceId);
            $this->assertEquals($paymentData['ip'], $payment->ip);
            $this->assertEquals($paymentData['callback_url'], $payment->callbackUrl);
            $this->assertEquals($paymentData['created_at'], $payment->createdAt);
            $this->assertEquals($paymentData['updated_at'], $payment->updatedAt);
        }

        if (isset($invoiceData['payment_id'])) {
            $this->assertEquals($invoiceData['payment_id'], $invoice->paymentId);
        }

        if (isset($invoiceData['paid_at'])) {
            $this->assertEquals($invoiceData['paid_at'], $invoice->paidAt);
        }

        if (isset($invoiceData['logo_url'])) {
            $this->assertEquals($invoiceData['logo_url'], $invoice->logoUrl);
        }
    }
}