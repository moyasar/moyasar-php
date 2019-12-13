<?php

namespace Moyasar;

class Moyasar
{
    const VERSION = '1.0.0';
    const API_BASE_URL = 'https://api.moyasar.com';
    const API_VERSION = 'v1';
    const CURRENT_VERSION_URL = self::API_BASE_URL . '/' . self::API_VERSION . '/';

    /**
     * Moyasar Service API Key
     * @var string
     */
    private static $apiKey;

    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }

    public static function getApiKey()
    {
        return self::$apiKey;
    }
}