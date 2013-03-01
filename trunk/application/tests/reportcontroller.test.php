<?php

require_once 'controllertestcase.php';

class TestReportcontroller extends ControllerTestCase
{

    public function testSample()
    {
        $this->assertTrue(true);
    }

    public function setUp()
    {
        $this->setupBeforeTests();
    }


    public function testGetSms()
    {
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->save();

        $student = FactoryMuff::create('Student');
        $student->schoolId = $school->id;

        $smsTransaction = FactoryMuff::create('SMSTransaction');
        $smsTransaction->studentId = $student->id;
        $smsTransaction->teacherId = NULL;
        $smsTransaction->userId = $user->id;
        $smsTransaction->status = 'pending';
        $smsTransaction->save();

        Auth::login($user->id);

        $parameters = array(
            'classSections' => array(),
        );

        Input::$json = (object)$parameters;
        $response = $this->post('report@post_getSMS', array());
        $this->assertEquals(200, $response->status());
    }
}