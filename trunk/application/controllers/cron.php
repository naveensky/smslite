<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/30/13
 * Time: 5:58 PM
 * To change this template use File | Settings | File Templates.
 */
class Cron_Controller extends Base_Controller
{
    public function action_runCron()
    {
        $cronRepo = CronRepository::getInstance();
        $cronRepo->sendmessage();
        echo "success";
    }

    public function action_test()
    {
        return Response::make(__('responseerror.sms_sent'), HTTPConstants::SUCCESS_CODE);
    }
}
