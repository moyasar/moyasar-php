<?php

namespace Tests\Unit;

use Moyasar\CreditCard;
use PHPUnit\Framework\TestCase;

class CreditCardSourceTest extends TestCase
{
    protected $data = [
        'type' => 'creditcard',
        'company' => 'visa',
        'name' => 'Power Rangers',
        'number' => 'XXXX-XXXX-XXXX-1111',
        'message' => 'Succeeded!',
        'transaction_url' => 'https://api.moyasar.com/v1/transaction_auths/<id>/form'
    ];

    public function test_type_prop_is_creditcard()
    {
        $this->assertEquals('creditcard', (new CreditCard)->type());
    }

    public function test_serialization_is_correct()
    {
        $data = $this->data;

        /** @var CreditCard $cc */
        $cc = CreditCard::fromJson(json_encode($data));

        $this->assertEquals($data['company'], $cc->company);
        $this->assertEquals($data['name'], $cc->name);
        $this->assertEquals($data['number'], $cc->number);
        $this->assertEquals($data['message'], $cc->message);
        $this->assertEquals($data['transaction_url'], $cc->transactionUrl);
    }

    public function test_serialization_must_not_change_cc_type()
    {
        $data = $this->data;

        $data['type'] = 'debitcard';

        /** @var CreditCard $cc */
        $cc = CreditCard::fromJson(json_encode($data));

        $this->assertEquals('creditcard', $cc->type());
    }
}