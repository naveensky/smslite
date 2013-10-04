<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 30/9/13
 * Time: 4:13 PM
 * To change this template use File | Settings | File Templates.
 */

class SMSTask extends Eloquent
{
    public static $hidden = array('id'); //to exclude id from the result\
    public static $table = 'tasks';
}