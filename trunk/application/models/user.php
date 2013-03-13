<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/30/13
 * Time: 4:34 PM
 * To change this template use File | Settings | File Templates.
 */
class User extends Eloquent
{
    public static $hidden = array('id', 'password', 'emailVerificationCode', 'mobileVerificationCode', 'forgottenPasswordCode', 'isDeleted', 'isDeactivated', 'reactivateCode', 'schoolId', 'isVerified'); //to exclude id from json array

    public static $factory = array(
        'email' => 'email',
        'password' => 'string',
        'mobile' => 'string',
        'schoolId' => 'factory|School'
    );

    public function roles()
    {
        return $this->has_many_and_belongs_to('Role');
    }

    public function school()
    {
        return $this->belongs_to('School', 'schoolId');
    }

}
