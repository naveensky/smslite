<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 3/8/13
 * Time: 6:24 PM
 * To change this template use File | Settings | File Templates.
 */

class SMSTemplate extends Eloquent
{
    public static $table = 'smsTemplate';
    public static $timestamps = true;

    public static $factory = array(
        'body' => 'string',
        'useCount' => '0',
        'schoolId' => 'factory|School'
    );
}