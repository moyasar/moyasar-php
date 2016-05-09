<?php
/**
 * Created by PhpStorm.
 * User: Sohib
 * Date: 5/8/16
 * Time: 5:36 PM
 */

include 'vendor/autoload.php';
include 'Client.php';
include "Payments.php";
use Moyasar\Client;
use Moyasar\Payments;

Client::setApiKey("onmPJfDStZHc4p1VkGycdJMu");


$pay = Payments::make([
    "amount" => "100",
    "source" => [
        "type" => "sadad",
        "username" => "alwafy6@gmail.com"
    ]
]);

var_dump($pay);
