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
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->save();

        Auth::login($user->id);

        $firstStudent = FactoryMuff::create('Student');
        $firstStudent->schoolId = $school->id;
        $firstStudent->classStandard = "6";
        $firstStudent->classSection = "A";
        $firstStudent->save();

        $firstStudent = FactoryMuff::create('Student');
        $firstStudent->schoolId = $school->id;
        $firstStudent->classStandard = "7";
        $firstStudent->classSection = "A";
        $firstStudent->save();

        $response = $this->get('school@get_classes');
        $this->assertEquals($response->status(), 200);

        $this->assertEquals(2, count(json_decode($response->content, true)));
    }

    public function testDepartments()
    {
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->save();

        Auth::login($user->id);

        $teacher = FactoryMuff::create('Teacher');
        $teacher->schoolId = $school->id;
        $teacher->department= "Hindi";
        $teacher->save();

        $teacher = FactoryMuff::create('Teacher');
        $teacher->schoolId = $school->id;
        $teacher->department= "English";
        $teacher->save();

        $response = $this->get('school@get_departments');
        $this->assertEquals($response->status(), 200);

        $this->assertEquals(2, count(json_decode($response->content, true)));
    }

}