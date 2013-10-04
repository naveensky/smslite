<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/4/13
 * Time: 12:10 PM
 * To change this template use File | Settings | File Templates.
 */

class SMSDispatcher_Task
{
    public $name = 'sms_dispatcher_task';
    private $smsRepo;
    private $lockService;


    public function __construct()
    {
        $this->smsRepo = new SMSRepository();
        $this->lockService = new LockService();
    }

    public function run($arguments)
    {
        try {
            $lockStatus = $this->lockService->lockStatus($this->name);
            if ($lockStatus) {
                return;
            }
            //get the lock
            $this->lockService->getLock($this->name);
            $allPendingSMS = $this->smsRepo->getAllPendingSMS(SMSTransaction::SMS_NORMAL_PRIORITY);
            if (!empty($allPendingSMS)) {
                foreach ($allPendingSMS as $sms) {
                    $smsStatus = SMSSend::sendMessages($sms);
                    if ($smsStatus)
                        $this->smsRepo->updateSMSStatus($sms->id, SMSTransaction::SMS_STATUS_SENT);
                }

            }
            //call function to mark failed sms which are not under cut off queue time
            $this->smsRepo->updateFailedSMSStatus(SMSTransaction::SMS_NORMAL_PRIORITY);
            //release Lock
            $this->lockService->freeLock($this->name, false);
        } catch (Exception $e) {
            Log::exception($e);
            $this->lockService->freelock($this->name, false);
        }
    }
}