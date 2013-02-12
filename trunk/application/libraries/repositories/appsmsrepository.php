<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 2/7/13
 * Time: 11:31 AM
 * To change this template use File | Settings | File Templates.
 */
class AppSMSRepository
{
    /**
     * Returns the number of credit to be used for sending SMS
     * @param $message - message to be sent
     * @return int - no of credits to be used
     */
    public function countCredits($message)
    {
        if (strlen($message) <= ConstantCredit::SINGLE_MESSAGE_LIMIT) {
            return ConstantCredit::SINGLE_CREDIT;
        }

        return ConstantCredit::DOUBLE_CREDIT;
    }


    public function formatMessage($message)
    {
        $message = trim($message);
        $message = strlen($message) > ConstantCredit::MAXIMUM_MESSAGE_LENGTH ?
            substr($message, 0, ConstantCredit::MAXIMUM_MESSAGE_LENGTH) : $message;
        return $message;
    }


    public function createAppSms($mobile, $message, $senderId, $userId)
    {
        $message = $this->formatMessage($message);
        $credits = $this->countCredits($message);
        $appSms = new AppSMSTransaction();
        $appSms->mobile = $mobile;
        $appSms->message = $message;
        $appSms->status = AppSMSTransaction::APP_SMS_STATUS_PENDING;
        $appSms->credits = $credits;
        $appSms->senderId = $senderId;
        $appSms->userId = $userId;
        try {
            $appSms->save();
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }

        return true;
    }

    public function updateAppSms($id, $status)
    {
        $data = array(
            'status' => $status
        );
        try {
            $sms = AppSMSTransaction::update($id, $data);
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }

        return true;
    }



}
