<?php
/**
 * Created by PhpStorm.
 * User: Sohib
 * Date: 5/9/16
 * Time: 10:44 PM
 */

namespace Moyasar;

class Payment
{
    const AMOUNT = "amount";
    const DESCRIPTION = "description";
    const SOURCE = "source";

    private static function validate($data)
    {

        if (empty($data[self::AMOUNT])) {
            throw  new \InvalidArgumentException("Amount is empty");
        } elseif (empty($data[self::SOURCE])) {
            throw  new \InvalidArgumentException("Source is empty");
        }

        $source = $data[self::SOURCE];

        if (!is_array($source)) {
            throw  new \InvalidArgumentException("Source must be an array");
        }

        if (isset($source["type"])) {
            if ($source["type"] == "creditcard") {

                if (!isset($source["name"])) {
                    throw  new \Exception("Put Card holder’s name in source[name]");
                }

                if (!isset($source["number"])) {
                    throw  new \Exception("card number must be in source[number]");
                }

                if (!isset($source["month"])) {
                    throw  new \Exception("Card  expiration month must be in source[month]");
                }

                if (!isset($source["year"])) {
                    throw  new \Exception("Card  expiration year must be in source[year]");
                }


            } elseif ($source["type"] == "sadad") {
                if (!isset($source["username"])) {
                    throw  new \Exception("Put Sadad username in source[username]");
                }

            } else {
                throw  new \Exception("source[type] must be sadad or creditcard only");
            }
        } else {
            throw  new \Exception("source[type] is missing");
        }

    }

    public static function refund($id, $amount = 0)
    {
        $data = [
            self::AMOUNT => $amount
        ];

        return json_decode(Client::post("https://api.moyasar.com/v1/payments/$id/refund", $data));
    }


    public static function fetch($id){
        return json_decode(Client::get("https://api.moyasar.com/v1/payments/$id"));
    }

    public static function all(){
        return json_decode(Client::get("https://api.moyasar.com/v1/payments"));

    }

    public static function update($id, $description = ""){
	    $data = [
		    self::DESCRIPTION => $description
	    ];
	    return json_decode(Client::put("https://api.moyasar.com/v1/payments/$id", $data));
    }

}
