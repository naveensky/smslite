<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/16/13
 * Time: 10:54 AM
 * To change this template use File | Settings | File Templates.
 */
class School extends Eloquent
{
    public static $hidden = array('id'); //to exclude id from json array

    public static $factory = array(
        'name' => 'string',
        'address' => 'string',
        'city' => 'string',
        'state' => 'string',
        'zip' => 'string',
        'senderId' => 'string',
        'contactPerson' => 'string',
        'contactMobile' => 'string',
        'code' => 'string'
    );


    public function teachers()
    {
        return $this->has_many('Teacher', 'schoolId');
    }

    public function students()
    {
        return $this->has_many('Student', 'schoolId');
    }

    public function users()
    {
        return $this->has_many('User', 'schoolId')->order_by('created_at', 'desc');
    }

}
