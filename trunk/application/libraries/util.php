<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 21/3/13
 * Time: 4:52 PM
 * To change this template use File | Settings | File Templates.
 */

class Util
{

    public static function is_in_role($role)
    {
        $roles=array();
        $userRoles=User::find(Auth::user()->id)->roles;
        foreach($userRoles as $userRole)
        {
            $roles[]=$userRole->name;
        }
        return in_array($role, $roles);
    }
}