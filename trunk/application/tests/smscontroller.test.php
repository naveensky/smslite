<?php

require_once 'controllertestcase.php';

/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/24/13
 * Time: 3:49 PM
 * To change this template use File | Settings | File Templates.
 */
class TestSmsController extends ControllerTestCase
{
    public function setUp()
    {
        $this->setupBeforeTests();
    }

    public function testDB()
    {
        $user = FactoryMuff::create('User');
        $user->save();
        $users = User::all();
        $this->assertEquals(1, sizeof($users));
    }

    public function testCompose()
    {
        $response = $this->get('sms@compose');
        $this->assertNotNull($response);
    }


//    public function testCreateSms()
//    {
//        $school = FactoryMuff::create('School');
//        $school->save();
//
//        $user = FactoryMuff::create('User');
//        $user->schoolId = $school->id;
//        $user->save();
//
//        Auth::login($user->id);
//        $student = FactoryMuff::create('Student');
//        $student->classStandard = "6";
//        $student->classSection = "A";
//        $student->schoolId = $school->id;
//        $student->save();
//
//        $student2 = FactoryMuff::create('student');
//        $student2->classStandard = "7";
//        $student2->classSection = "A";
//        $student2->schoolId = $school->id;
//        $student2->save();
//
//        $teacher = FactoryMuff::create('Teacher');
//        $teacher->schoolId = $school->id;
//        $teacher->department = "Hindi";
//        $teacher->save();
//
//        $teacher2 = FactoryMuff::create('Teacher');
//        $teacher2->schoolId = $school->id;
//        $teacher2->department = "English";
//        $teacher2->save();
//
//        $studentCodes = array(
//            $student->code,
//            $student2->code
//        );
//
//        $teacherCodes = array(
//            $teacher->code,
//            $teacher2->code
//        );
//
//        $message = "Dear parents, your child was absent today.";
//        $parameters = (object)array('studentCodes' => $studentCodes,
//            'teacherCodes' => $teacherCodes,
//            'message' => $message,
//            'user_id' => $user->id,
//            'sender_id' => 'GAPS'
//        );
//
//        Input::$json = $parameters;
//        $response = $this->post('SMS@post_create', array());
//        $this->assertEquals(200,$response->status());
//    }


//    public function testaddsms()
//    {
////        Auth::login(1);
////        $parameters = array(
////            'classSections'=>array('8-A'),
////            'message'=>"Dear Parents, your child name"
////        );
////        Input::$json = (object)$parameters;
////        $response = $this->post('SMS@post_send_to_class', array());
////        var_dump($response);
//    }

//    public function testbusroute()
//    {
//        Auth::login(1);
//        $parameters = array(
//            'morningBusRoutes'=>array('70','77','647'),
//            'eveningBusRoutes'=>array('8887'),
//            'message'=>"Dear parents, teacher"
//        );
//        Input::$json = (object)$parameters;
//        $response = $this->post('SMS@post_send_to_busroutes', array());
//        var_dump($response);
//    }

//    public function testDepartment()
//    {
//        Auth::login(1);
//        $parameters = array(
//            'department' => array('hindi','english'),
//            'message' => "Dear parents, teacher"
//        );
//        Input::$json = (object)$parameters;
//        $response = $this->post('SMS@post_send_to_department', array());
//        var_dump($response);
//
//    }
}
