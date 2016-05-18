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

    private static function validate($params)
    {

        // TODO :: valdiate params


        if(! isset($params["amount"])     ){
            throw new \Exception("Missing Parameter : amount key dosnt exist");
        }

        if(! isset($params["source"])     ){
            throw new \Exception("Missing Parameter : source key dosnt exist");
        }


    }
                                                    
    public static function make($params)
    {
        self::validate($params);
        return Client::post("https://api.moyasar.com/v1/payments",$params);
    }


}