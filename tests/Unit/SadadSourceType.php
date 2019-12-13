<?php

namespace Tests\Unit;

use Moyasar\Sadad;
use PHPUnit\Framework\TestCase;

class SadadSourceType extends TestCase
{
    protected $data = [
        "type" => "sadad",
        "username" => "aliioe",
        "error_code" => 123,
        "message" => 'some message',
        "transaction_id" => '123-abc',
        "transaction_url" => 'http://example.com'
    ];

    public function test_type_prop_is_sadad()
    {
        $this->assertEquals('sadad', (new Sadad())->type());
    }

    public function test_serialization_is_correct()
    {
        $data = $this->data;

        /** @var Sadad $cc */
        $cc = Sadad::fromJson(json_encode($data));

        $this->assertEquals($data['username'], $cc->username);
        $this->assertEquals($data['error_code'], $cc->errorCode);
        $this->assertEquals($data['message'], $cc->message);
        $this->assertEquals($data['transaction_id'], $cc->transactionId);
        $this->assertEquals($data['transaction_url'], $cc->transactionUrl);
    }

    public function test_serialization_must_not_change_cc_type()
    {
        $data = $this->data;

        $data['type'] = 'sadad-new';

        /** @var Sadad $cc */
        $cc = Sadad::fromJson(json_encode($data));

        $this->assertEquals('sadad', $cc->type());
    }
}