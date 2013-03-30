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
        $roles = array();
        $userRoles = User::find(Auth::user()->id)->roles;
        foreach ($userRoles as $userRole) {
            $roles[] = $userRole->name;
        }
        return in_array($role, $roles);
    }

    public static function getFromDate($toDate)
    {
        if (empty($toDate))
            return $toDate;
        $dateid = date('Y', $toDate->getTimestamp()) . "-" . date('m', $toDate->getTimestamp()) . "-" . date('d', $toDate->getTimestamp()) . " 00:00:00";
        $toDate = new DateTime($dateid);
        $toDate->sub(new DateInterval('P1D'));
        return $toDate;
    }

    public static function getToDate($toDate)
    {
        if (empty($toDate))
            return $toDate;
        $dateid = date('Y', $toDate->getTimestamp()) . "-" . date('m', $toDate->getTimestamp()) . "-" . date('d', $toDate->getTimestamp()) . " 00:00:00";
        $toDate = new DateTime($dateid);
        $toDate->add(new DateInterval('P1D'));
        return $toDate;
    }
}