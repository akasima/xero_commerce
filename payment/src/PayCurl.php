<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 29/10/2018
 * Time: 2:12 PM
 */

namespace Xpressengine\XePlugin\XeroPay;


class PayCurl
{

    public static function post($url, $option, $parameter)
    {
        $curl = curl_init($url);

        $post_array = array();
        if (is_array($parameter)) {
            foreach ($parameter as $key => $value) {
                $post_array[] = urlencode($key) . "=" . urlencode($value);
            }

            $post_string = implode("&", $post_array);
        } else {
            $post_string = $option;
        }

        $option[CURLOPT_POST] = true;
        $option[CURLOPT_POSTFIELDS] = $post_string;
        $option[CURLOPT_RETURNTRANSFER] = true;

        curl_setopt_array($curl, $option);
        $res = curl_exec($curl);
        curl_close($curl);
        return json_decode($res);
    }
}
