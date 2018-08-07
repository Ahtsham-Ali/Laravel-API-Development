<?php

namespace App\Helper;



use App\Constant\Constant;

class Helper
{
    #this function for call nhtsa APi
    public static function ApiUrl($year, $company, $model)
    {
        return (Constant::$url.$year.Constant::$make.$company.Constant::$model.$model.Constant::$format);

    }
#this function for multiple  Queries
    public static function ApiUrlById($id)
    {
        return ( Constant::$QueryUrl . $id .Constant::$format);
    }

    // PHP CURL command to execute URLs/APIs with get request
    public static function curlRequest($url)

    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        return json_decode($response);
    }
}