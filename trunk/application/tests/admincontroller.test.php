<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 25/3/13
 * Time: 3:51 PM
 * To change this template use File | Settings | File Templates.
 */
require_once 'controllertestcase.php';

class TestAdmincontroller extends ControllerTestCase
{

    public function setUp()
    {
        $this->setupBeforeTests();
    }

    public function tearDown()
    {
        $this->tearDownAfterTests();
    }

    public function testSample()
    {
        $this->assertTrue(true);
    }

    public function testPostAllocateCredits()
    {
        Bundle::start('messages');
        $user = $this->getSampleUser();
        $school = $user->school()->first();
        $role = new Role(array('name' => 'superadmin'));
        $role->save();
        $user->roles()->attach($role->id);
        $smsCredit = FactoryMuff::create('SmsCredit');
        $smsCredit->schoolId = $school->id;
        $smsCredit->credits = 25;
        $smsCredit->save();

        Auth::login($user->id);
        $parameters = array(
            'school' => $school->code,
            'credits' => "1000",
            'amount' => "1000",
            'discount' => "",
            'remarks' => "Hello new credits allocated",
            'sender_id' => 'GAPS',
            'sendToSchool' => true
        );

        Input::$json = (object)$parameters;
        $response = $this->post('admin@post_allocate_credits', array());
        $this->assertEquals(200, $response->status());

    }

    public function testGetSchoolsList()
    {
        $user = $this->getSampleUser();
        $school = $user->school()->first();
        $role = new Role(array('name' => 'superadmin'));
        $role->save();
        $user->roles()->attach($role->id);
        Auth::login($user->id);
        $smsCredit = FactoryMuff::create('SmsCredit');
        $smsCredit->schoolId = $school->id;
        $smsCredit->credits = 50;
        $smsCredit->save();

        $parameters = array(
            'name' => '',
            'email' => '',
        );

        Input::$json = (object)$parameters;
        $response = $this->post('admin@post_schools_list', array());
        $result = json_decode($response->content);
        $this->assertEquals(1, count($result));
    }

    public function testGetSMSLog()
    {
        $user = $this->getSampleUser();
        $school = $user->school()->first();
        $role = new Role(array('name' => 'superadmin'));
        $role->save();
        $user->roles()->attach($role->id);
        Auth::login($user->id);

        $smsCredit = FactoryMuff::create('SmsCredit');
        $smsCredit->schoolId = $school->id;
        $smsCredit->credits = 50;
        $smsCredit->save();

        $smsTemplate = FactoryMuff::create('smsTemplate');
        $smsTemplate->schoolId = $school->id;
        $smsTemplate->body = 'Dear Parents, <%text_teacher_name%> is asking for a meet on <%text_PTM_date%>';
        $smsTemplate->save();

        $smsTemplate2 = FactoryMuff::create('smsTemplate');
        $smsTemplate2->schoolId = $school->id;
        $smsTemplate2->body = 'Dear Parent, your child <%name%> was absent on <%today%>.Hope everything is fine';
        $smsTemplate2->save();

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

        $studentCodes = array(
            (object)array('code' => $student->code, 'mobileCount' => 1),
            (object)array('code' => $student2->code, 'mobileCount' => 1)
        );

        $teacherCodes = array(
            (object)array('code' => $teacher->code, 'mobileCount' => 1),
            (object)array('code' => $teacher2->code, 'mobileCount' => 1)
        );

        $message = "Dear parents, your child was absent today.";
        $parameters = (object)array('studentCodes' => $studentCodes,
            'teacherCodes' => $teacherCodes,
            'message' => $message,
            'sender_id' => 'GAPS',
            'templateId' => 0,
            'sendCopy' => true
        );
        Input::$json = $parameters;
        $response = $this->post('SMS@post_create', array());
        $this->assertEquals(200, $response->status());

        $parameters = array(
            'toDate' => '2013-07-25',
            'fromDate' => '2013-07-01',
        );

        Input::$json = (object)$parameters;
        $response = $this->post('admin@post_sms_report', array());
        $this->assertEquals(200, $response->status());
    }

}