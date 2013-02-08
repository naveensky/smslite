<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/21/13
 * Time: 11:56 AM
 * To change this template use File | Settings | File Templates.
 */
class Teacher extends Eloquent
{

    public static $hidden = array('id'); //to exclude id from the result

    public function school()
    {
        return $this->belongs_to('School');
    }

}
