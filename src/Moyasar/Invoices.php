<?php
/**
 * Created by PhpStorm.
 * User: Sohib
 * Date: 5/19/16
 * Time: 9:03 PM
 */

namespace Moyasar;


class Invoices
{
    const AMOUNT = "amount";
    const CURRENCY = "currency";
    const DESCRIPTION = "description";

    public static function make($amount, $description, $currency = "SAR")
    {

        $data = [
            self::AMOUNT => $amount,
            self::DESCRIPTION => $description,
            self::CURRENCY => $currency
        ];

        return json_decode(Client::post("https://api.moyasar.com/v1/invoices", $data));
    }

    public static function retrieve($id){
        return json_decode(Client::get("https://api.moyasar.com/v1/invoices/$id"));
    }

    public static function all(){
        return json_decode(Client::get("https://api.moyasar.com/v1/invoices"));
    }


}