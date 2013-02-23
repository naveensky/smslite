<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/30/13
 * Time: 6:30 PM
 * To change this template use File | Settings | File Templates.
 */
class CronRepository
{
    private static $_instance;
    private $isCronRunning = false;
    private $smsRepo;

    private function __construct()
    {
        $this->smsRepo = new SMSRepository();
    }

    public static function getInstance()
    {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function sendMessage()
    {
        //return if cron is already running
        if ($this->isCronRunning)
            return;

        //set cron as running
        $this->isCronRunning = true;

        //get all pending sms to be sent
        $pendingSMS = $this->smsRepo->getAllPendingSMS();

        if (!empty($pendingSMS)) {
            $username = Config::get('sms.username');
            $password = Config::get('sms.password');

            foreach ($pendingSMS as $sms) {
                //create api url to hit
                $sms_url = Config::get('sms.url');
                $sms_url = "$sms_url ?username=$username&password=$password&mobile=$sms->mobile&senderId=$sms->senderId&message=$sms->message";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $sms_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, '6');
                $result = curl_exec($ch);
                $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if ($http_status == HTTPConstants::SUCCESS_CODE)
                    $this->smsRepo->updateStatus($sms->id, "sent");
            }
        }
        //mark cron as free for next request
        $this->isCronRunning = false;
    }
}
