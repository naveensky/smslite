<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/4/13
 * Time: 11:58 AM
 * To change this template use File | Settings | File Templates.
 */

class SMSSend
{

    public static function sendMessages($pendingSMS)
    {
        $username = Config::get('sms.username');
        $password = Config::get('sms.password');
        $SMSStatus = array();
        foreach ($pendingSMS as $sms) {
            //create api url to hit
            $sms_url = Config::get('sms.url');
            $sms_url = "$sms_url?username=$username&password=$password&sendername=$sms->senderId&mobileno=$sms->mobile&message=" . urlencode($sms->message);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $sms_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, '6');
            $result = curl_exec($ch);
            $error = curl_error($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($http_status == HTTPConstants::SUCCESS_CODE)
                $SMSStatus[$sms->id] = true;
            else
                $SMSStatus[$sms->id] = false;
        }

        return $SMSStatus;
    }
}