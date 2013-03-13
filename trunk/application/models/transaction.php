<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 13/3/13
 * Time: 4:08 PM
 * To change this template use File | Settings | File Templates.
 */

class Transaction extends Eloquent
{

    public static $hidden = array('id'); //to exclude id from json array
    public static $timestamps = true;

    public static $factory = array(
        'orderId' => 'string',
        'smsCredits' => 100,
        'amount' => 100.56,
        'discount' => 10.51,
        'grossAmount' => 90.06,
        'schoolId' => 'factory|School'
    );
}