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


Client::setApiKey("sk_test_fgFC9Qgk7d9wrXfLS655exThoktXW6d9ehjBVw79");


$pay = Payments::make("10000", [
    "type" => Payments::CREDIT_CARD,
    "name" => "Sohib H Algotimel",
    "number" => "4111111111111111",
    "cvc" => 331,
    "month" => 12,
    "year" => 2017
]);
//411111111111111
var_dump(Payments::all());
