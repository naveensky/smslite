<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 2/23/13
 * Time: 11:59 AM
 * To change this template use File | Settings | File Templates.
 */
class SendSMS_Task
{

    public function run($arguments)
    {

        $cronRepo = CronRepository::getInstance();
        $cronRepo->sendmessage();
        return "success";
    }
}
