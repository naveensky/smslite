<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 2/6/13
 * Time: 1:58 PM
 * To change this template use File | Settings | File Templates.
 */
class AppSMSTransaction extends Eloquent
{
    public static $table = 'appSmsTransactions';
    const APP_SMS_STATUS_PENDING='pending';
    const APP_SMS_STATUS_FAIL='fail';
    const APP_SMS_STATUS_SENT='sent';

    public static $factory = array(
        'mobile' => 'string',
        'message' => 'string',
        'credits' => 2,
        'senderId' => 'string',
        'userId' => 'factory|User',
        'status' => 'string'
    );
}
