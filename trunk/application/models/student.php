<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/17/13
 * Time: 1:12 PM
 * To change this template use File | Settings | File Templates.
 */
class Student extends Eloquent
{
    public static $hidden = array('id'); //to exclude id from the result

    public function school()
    {
        return $this->belongs_to('School');
    }
}
