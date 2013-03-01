<?php

require_once 'controllertestcase.php';

class TestReportcontroller extends ControllerTestCase
{

    public function setUp()
    {
        $this->setupBeforeTests();
        $this->loadSession();
    }

    protected static function loadSession()
    {
        \Session::started() or \Session::load();
    }

    public function testGetSms()
    {
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->save();

        $student = FactoryMuff::create('Student');
        $student->classStandard = "6";
        $student->classSection = "A";
        $student->schoolId = $school->id;
        $student->save();

        $student2 = FactoryMuff::create('student');
        $student2->classStandard = "7";
        $student2->classSection = "A";
        $student2->schoolId = $school->id;
        $student2->save();

        $teacher = FactoryMuff::create('Teacher');
        $teacher->schoolId = $school->id;
        $teacher->department = "Hindi";
        $teacher->save();

        $teacher2 = FactoryMuff::create('Teacher');
        $teacher2->schoolId = $school->id;
        $teacher2->department = "English";
        $teacher2->save();

        $smsTransaction1 = FactoryMuff::create('SMSTransaction');
        $smsTransaction1->studentId = $student->id;
        $smsTransaction1->teacherId = NULL;
        $smsTransaction1->userId = $user->id;
        $smsTransaction1->status = 'pending';
        $smsTransaction1->save();

        $smsTransaction2 = FactoryMuff::create('SMSTransaction');
        $smsTransaction2->studentId = $student2->id;
        $smsTransaction2->teacherId = NULL;
        $smsTransaction2->userId = $user->id;
        $smsTransaction2->status = 'pending';
        $smsTransaction2->save();

        $smsTransaction3 = FactoryMuff::create('SMSTransaction');
        $smsTransaction3->studentId = NULL;
        $smsTransaction3->teacherId = $teacher->id;
        $smsTransaction3->userId = $user->id;
        $smsTransaction3->status = 'pending';
        $smsTransaction3->save();

        $smsTransaction4 = FactoryMuff::create('SMSTransaction');
        $smsTransaction4->studentId = NULL;
        $smsTransaction4->teacherId = $teacher2->id;
        $smsTransaction4->userId = $user->id;
        $smsTransaction4->status = 'pending';
        $smsTransaction4->save();


        Auth::login($user->id);

        $parameters = array(
            'classSections' => array("7-A","6-A"),
        );

        Input::$json = (object)$parameters;
        $response = $this->post('report@post_getSMS', array());
        $this->assertEquals(200, $response->status());
        $this->assertEquals(2, count(json_decode($response->content, true)));


    }
}