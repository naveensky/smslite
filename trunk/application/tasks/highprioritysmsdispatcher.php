<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/4/13
 * Time: 1:24 PM
 * To change this template use File | Settings | File Templates.
 */

class HighPrioritySMSDispatcher_Task
{

    public static $isRunning = false;
    private $smsRepo;

    public function __construct()
    {
        $this->smsRepo = new SMSRepository();
    }

    public function run($arguments)
    {
        if (self::$isRunning)
            return;
        try {
            self::$isRunning = true;
            $allPendingSMS = $this->smsRepo->getAllPendingSMS(SMSTransaction::SMS_HIGH_PRIORITY);
            if (!empty($allPendingSMS)) {
                $smsStatus = SMSSend::sendMessages($allPendingSMS);
                $smsSendIds = array();
                foreach ($smsStatus as $key => $value) {
                    if ($value)
                        $smsSendIds[] = $key;
                }
                $this->smsRepo->updateStatus($smsSendIds, SMSTransaction::SMS_STATUS_SENT);
            }
        } catch (Exception $e) {
            Log::exception($e);
            self::$isRunning = false;
        }
        $this->smsRepo->updateFailedSMSStatus(SMSTransaction::SMS_HIGH_PRIORITY);
        self::$isRunning = false;
    }
}