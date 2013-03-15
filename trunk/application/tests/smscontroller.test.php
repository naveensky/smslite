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


    public function testCreateSms()
    {
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->save();

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
            'sendCopy'=>true
        );

        Input::$json = $parameters;
        $response = $this->post('SMS@post_create', array());
        $this->assertEquals(200, $response->status());

        $content = json_decode($response->content, true);
        $this->assertEquals(true, $content['status']);


        $parameters = (object)array('studentCodes' => array(),
            'teacherCodes' => array(),
            'message' => $message,
            'sender_id' => 'GAPS',
            'templateId' => 0,
            'sendCopy'=>true
        );

        Input::$json = $parameters;
        $response = $this->post('SMS@post_create', array());
        $this->assertEquals(400, $response->status());

        $studentCodes = array(
            (object)array('code' => $student->code, 'mobileCount' => 1),
            (object)array('code' => $student2->code, 'mobileCount' => 2)
        );

        $teacherCodes = array(
            (object)array('code' => $teacher->code, 'mobileCount' => 1),
            (object)array('code' => $teacher2->code, 'mobileCount' => 2)
        );

        $message = "Dear parents, your child was absent today.";
        $templateID = $smsTemplate2->id;

        $parameters = (object)array('studentCodes' => $studentCodes,
            'teacherCodes' => $teacherCodes,
            'message' => $message,
            'sender_id' => 'GAPS',
            'messageVars' => array('text_teacher_name' => 'Naveen Gupta', 'text_PTM_date' => '8 march 2013'),
            'templateId' => $templateID,
            'sendCopy'=>false
        );

        Input::$json = $parameters;
        $response = $this->post('SMS@post_create', array());
        $this->assertEquals(200, $response->status());
    }
}
