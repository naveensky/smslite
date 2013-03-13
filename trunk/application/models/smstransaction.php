<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/23/13
 * Time: 3:14 PM
 * To change this template use File | Settings | File Templates.
 */
class SMSTransaction extends Eloquent
{
    const SMS_STATUS_PENDING = 'pending';
    const SMS_STATUS_FAIL = 'fail';
    const SMS_STATUS_SENT = 'sent';

    public static $table = 'smsTransactions';
    public static $hidden = array('id'); //to exclude id from json array
    public static $timestamps = true;

    public static $factory = array(
        'mobile' => 'string',
        'message' => 'string',
        'credits' => 2,
        'senderId' => 'string',
        'userId' => 'factory|User',
        'status' => 'string'
    );
}
