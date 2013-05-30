<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 28/5/13
 * Time: 3:09 PM
 * To change this template use File | Settings | File Templates.
 */

class Rest_Controller extends Base_Controller
{
    public function action_getStudents()
    {
        $student1 = array("admissionNo" => 12456, "name" => 'Raman', 'email' => 'raman@gmail.com',
            'fatherName' => '', 'motherName' => '', 'mobile1' => '9999999999', 'mobile2' => '9999999999',
            'mobile3' => '9999999999', 'mobile4' => '9999999999', 'mobile5' => '', 'dob' => '', 'classStandard' => '6',
            'classSection' => 'A', 'morningBusRoute' => '400', 'eveningBusRoute' => '500', "gender" => "male");
        $student2 = array("admissionNo" => 12457, "name" => 'Keshav', 'email' => 'keshav@gmail.com',
            'fatherName' => '', 'motherName' => '', 'mobile1' => '9999999999', 'mobile2' => '9999999999',
            'mobile3' => '9999999999', 'mobile4' => '9999999999', 'mobile5' => '', 'dob' => '', 'classStandard' => '6',
            'classSection' => 'A', 'morningBusRoute' => '400', 'eveningBusRoute' => '500', "gender" => "male");
        $student = array($student1, $student2);
        return Response::json($student);
    }
}