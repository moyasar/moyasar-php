<?php
/**
 * Created by PhpStorm.
 * User: Sohib
 * Date: 5/9/16
 * Time: 10:44 PM
 */

namespace Moyasar;


class Payments
{

    private static function validate()
    {

        // TODO :: valdiate params
    }

    public static function make($params)
    {
        self::validate();
        return Client::post("https://api.moyasar.com/v1/payments",$params);


    }


}