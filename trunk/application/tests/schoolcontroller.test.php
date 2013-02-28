<?php

require_once 'controllertestcase.php';

class TestSchoolController extends ControllerTestCase
{

    public function setUp()
    {
        $this->setupBeforeTests();
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
}