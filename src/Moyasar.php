<?php

namespace Moyasar;

class Moyasar
{
    const VERSION = '1.0.2';
    const API_BASE_URL = 'https://api.moyasar.com';
    const API_VERSION = 'v1';
    const CURRENT_VERSION_URL = self::API_BASE_URL . '/' . self::API_VERSION . '/';

    /**
     * Moyasar Service API Key
     * @var string
     */
    private static $apiKey;

    /**
     * Moyasar Service API Publishable Key
     * @var string
     */
    private static $publishableApiKey;

    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }

    public static function getApiKey()
    {
        return self::$apiKey;
    }

    public static function setPublishableApiKey($publishableApiKey)
    {
        self::$publishableApiKey = $publishableApiKey;
    }

    public static function getPublishableApiKey()
    {
        return self::$publishableApiKey;
    }
}