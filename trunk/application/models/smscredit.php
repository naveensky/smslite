<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 2/19/13
 * Time: 1:47 PM
 * To change this template use File | Settings | File Templates.
 */
class SMSCredit extends Eloquent
{
    public static $table = 'smsCredits';
    public static $hidden = array('id'); //to exclude id from json array
    public static $timestamps = true;

}
