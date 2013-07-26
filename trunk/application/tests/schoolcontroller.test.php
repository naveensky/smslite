<?php

require_once 'controllertestcase.php';

class TestSchoolController extends ControllerTestCase
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

    public function testGetClasses()
    {
        $user = $this->getSampleUser();
        Auth::login($user->id);

        $firstStudent = FactoryMuff::create('Student');
        $firstStudent->schoolId = $user->school()->first()->id;
        $firstStudent->classStandard = "6";
        $firstStudent->classSection = "A";
        $firstStudent->save();

        $firstStudent = FactoryMuff::create('Student');
        $firstStudent->schoolId = $user->school()->first()->id;
        $firstStudent->classStandard = "7";
        $firstStudent->classSection = "A";
        $firstStudent->save();

        $response = $this->get('school@get_classes');
        $this->assertEquals($response->status(), 200);

        $this->assertEquals(2, count(json_decode($response->content, true)));
    }

    public function testDepartments()
    {
        $user = $this->getSampleUser();
        Auth::login($user->id);

        $teacher = FactoryMuff::create('Teacher');
        $teacher->schoolId = $user->school()->first()->id;
        $teacher->department = "Hindi";
        $teacher->save();

        $teacher = FactoryMuff::create('Teacher');
        $teacher->schoolId = $user->school()->first()->id;
        $teacher->department = "English";
        $teacher->save();

        $response = $this->get('school@get_departments');
        $this->assertEquals($response->status(), 200);

        $this->assertEquals(2, count(json_decode($response->content, true)));
    }

    public function testGetMorningRoutes()
    {
        $user = $this->getSampleUser();
        $school = $user->school()->first();

        Auth::login($user->id);

        $student = FactoryMuff::create('Student');
        $student->schoolId = $school->id;
        $student->morningBusRoute = "1";
        $student->save();

        $teacher = FactoryMuff::create('Teacher');
        $teacher->schoolId = $user->school()->first()->id;
        $teacher->morningBusRoute = "2";
        $teacher->save();

        $response = $this->get('school@get_morning_routes');
        $this->assertEquals(200, $response->status());

        $routes = json_decode($response->content, true);

        $this->assertEquals(2, count($routes));

        $response = $this->get('school@get_morning_routes', array('ignoreStudents' => true));
        $this->assertEquals(200, $response->status());

        $routes = json_decode($response->content, true);
        $this->assertEquals(1, count($routes));

        $response = $this->get('school@get_morning_routes', array('ignoreTeachers' => true));
        $this->assertEquals(200, $response->status());

        $routes = json_decode($response->content, true);
        $this->assertEquals(1, count($routes));
    }

    public function testGetEveningRoutes()
    {
        $user = $this->getSampleUser();
        $school = $user->school()->first();

        Auth::login($user->id);

        $student = FactoryMuff::create('Student');
        $student->schoolId = $school->id;
        $student->morningBusRoute = "1";
        $student->save();

        $teacher = FactoryMuff::create('Teacher');
        $teacher->schoolId = $user->school()->first()->id;
        $teacher->morningBusRoute = "2";
        $teacher->save();

        $response = $this->get('school@get_evening_routes');
        $this->assertEquals(200, $response->status());

        $routes = json_decode($response->content, true);

        $this->assertEquals(2, count($routes));

        $response = $this->get('school@get_evening_routes', array('ignoreStudents' => true));
        $this->assertEquals(200, $response->status());

        $routes = json_decode($response->content, true);
        $this->assertEquals(1, count($routes));

        $response = $this->get('school@get_evening_routes', array('ignoreTeachers' => true));
        $this->assertEquals(200, $response->status());

        $routes = json_decode($response->content, true);
        $this->assertEquals(1, count($routes));
    }

    public function testGetAvailableCredits()
    {
        $user = $this->getSampleUser();
        $school = $user->school()->first();

        Auth::login($user->id);

        $smsCredit = FactoryMuff::create('SmsCredit');
        $smsCredit->schoolId = $school->id;
        $smsCredit->credits = 25;
        $smsCredit->save();

        $response = $this->get('school@get_available_credits');
        $this->assertEquals(200, $response->status());
        $smsCredits = json_decode($response->content);
        $this->assertEquals(25, $smsCredits);
    }

    public function testSchoolUpdate()
    {
        $user = $this->getSampleUser();
        $school = $user->school()->first();

        Auth::login($user->id);
        $parameters = array(
            'name' => "Salwan Public School",
            'address' => "Karol Bagh",
            'city' => "New Delhi",
            'state' => "Delhi",
            'zip' => "110067",
            'sender_id' => 'GAPS',
            'contact_person' => 'Anuj Kumar',
            'contact_mobile' => '999999999'
        );

        Input::$json = (object)$parameters;
        $response = $this->post('school@post_update', array());
        $this->assertEquals(200, $response->status());
    }

    public function testGetAvailableTemplates()
    {
        $user = $this->getSampleUser();
        $school = $user->school()->first();

        Auth::login($user->id);

        $smsTemplate = FactoryMuff::create('smsTemplate');
        $smsTemplate->schoolId = $school->id;
        $smsTemplate->body = 'Dear Parents, <%text_teacher_name%> is asking for a meet on <%text_PTM_date%>';
        $smsTemplate->save();

        $smsTemplate2 = FactoryMuff::create('smsTemplate');
        $smsTemplate2->schoolId = $school->id;
        $smsTemplate2->body = 'Dear Parent, your child <%name%> was absent on <%today%>.Hope everything is fine';
        $smsTemplate2->save();

        $response = $this->get('school@get_available_templates');
        $this->assertEquals(200, $response->status());
        $this->assertEquals(2, count(json_decode($response->content, true)));

    }

    public function testGetAllSchools()
    {
        $user = $this->getSampleUser();
        Auth::login($user->id);
        $response = $this->get('school@get_all_schools');
        $this->assertEquals(200, $response->status());
        $this->assertEquals(2, count(json_decode($response->content, true)));

    }

    public function testGetStudentOrTeachersFromBusRoutes()
    {
        $user = $this->getSampleUser();
        $school = $user->school()->first();

        Auth::login($user->id);
        $student1 = FactoryMuff::create('Student');
        $student1->schoolId = $school->id;
        $student1->morningBusRoute = "400";
        $student1->eveningBusRoute = '500';
        $student1->save();

        $student2 = FactoryMuff::create('Student');
        $student2->schoolId = $school->id;
        $student2->morningBusRoute = "1000";
        $student1->eveningBusRoute = '500';
        $student2->save();

        $teacher1 = FactoryMuff::create('Teacher');
        $teacher1->schoolId = $user->school()->first()->id;
        $student1->eveningBusRoute = '400';
        $teacher1->morningBusRoute = "500";
        $teacher1->save();

        $teacher2 = FactoryMuff::create('Teacher');
        $teacher2->schoolId = $user->school()->first()->id;
        $teacher2->morningBusRoute = "2";
        $student1->eveningBusRoute = '400';
        $teacher2->save();

        $parameters = array(
            'morningBusRoutes' => array($student1->morningBusRoute, $teacher2->mornigBusRoute),
            'eveningBusRoutes' => array()
        );

        Input::$json = (object)$parameters;

        $response = $this->post('school@get_students_or_teachers_from_bus_routes', array());
        $result = json_decode($response->content);
        $students = $result->students;
        $teachers = $result->teachers;
        $this->assertEquals(200, $response->status());
        $this->assertEquals(1, count($students));
        $this->assertEquals(0, count($teachers));

        $parameters1 = array(
            'morningBusRoutes' => array($student1->morningBusRoute, $teacher1->morningBusRoute),
            'eveningBusRoutes' => array($teacher1->eveningBusRoute)
        );

        Input::$json = (object)$parameters1;

        $response = $this->post('school@get_students_or_teachers_from_bus_routes', array());
        $result = json_decode($response->content);
        $students = $result->students;
        $teachers = $result->teachers;
        $this->assertEquals(200, $response->status());
        $this->assertEquals(1, count($students));
        $this->assertEquals(1, count($teachers));

    }
}