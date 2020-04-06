<?php

namespace Tests\Unit;

use Moyasar\Moyasar;
use PHPUnit\Framework\TestCase;

class MoyasarTest extends TestCase
{
    public function test_base_url_is_correct()
    {
        $this->assertEquals('https://api.moyasar.com', Moyasar::API_BASE_URL);
    }

    public function test_api_version_is_v1()
    {
        $this->assertEquals('v1', Moyasar::API_VERSION);
    }

    public function test_current_version_url_is_constructed_correctly()
    {
        $this->assertEquals('https://api.moyasar.com/v1/', Moyasar::CURRENT_VERSION_URL);
    }

    public function test_current_version_url_must_have_trailing_slash()
    {
        $this->assertTrue(preg_match('/.+\/$/', Moyasar::CURRENT_VERSION_URL) == true);
    }

    public function test_user_is_able_to_set_and_get_api_key()
    {
        Moyasar::setApiKey('123');
        $this->assertEquals('123', Moyasar::getApiKey());
    }
}