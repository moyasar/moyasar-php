<?php
/**
 * Created by PhpStorm.
 * User: Sohib
 * Date: 5/8/16
 * Time: 5:37 PM
 */

namespace Moyasar;

use GuzzleHttp;
use HttpRequestNotFound;

class Client
{

//    const base_uri = "https://api.moyasar.com/{version}";

    public static $apiKey;

    /**
     * @param mixed $apiKey
     */
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }

    public static function get($request, $options = [])
    {
        $options = [
            'auth' => [self::$apiKey, '']
        ];

        $client = new GuzzleHttp\Client();

        $response = $client->get($request, $options);
        if ($response->getStatusCode() == '200' OR '201') {
            return $response->getBody()->getContents();
        }

        throw new HttpRequestNotFound($response->getStatusCode() . ' status code returned');
    }


    public static function post($request, $options = [])
    {
//        var_dump(json_encode($options));
        $client = new GuzzleHttp\Client();

        $options["auth"] =  [self::$apiKey, ''];

        $response = $client->post($request, $options);
//        $response = $request->send();

        if ($response->getStatusCode() == '200' OR '201') {
            return $response->getBody()->getContents();
        }
        throw new HttpRequestNotFound($response->getStatusCode() . ' status code returned');
    }


}


