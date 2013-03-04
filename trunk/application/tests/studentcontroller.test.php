<?php

require_once 'controllertestcase.php';

class TestStudentController extends ControllerTestCase
{

    public function setUp()
    {
        $this->setupBeforeTests();

    }

    public function testGetStudent()
    {
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->save();

        Auth::login($user->id);

        $student = FactoryMuff::create('Student');
        $student->classStandard = "6";
        $student->classSection = "A";
        $student->schoolId = $school->id;
        $student->save();

        $parameters = array(
            'code' => $student->code
        );

        $response = $this->get('student@get', $parameters);
        $this->assertEquals(200, $response->status());
    }

    public function testDeleteStudent()
    {
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->save();

        Auth::login($user->id);

        $student = FactoryMuff::create('Student');
        $student->classStandard = "6";
        $student->classSection = "A";
        $student->schoolId = $school->id;
        $student->save();
        $parameters = array(
            'code' => $student->code
        );

        $response = $this->get('student@delete', $parameters);
        $this->assertEquals(200, $response->status());
    }


//    public function testupdate()
//    {
//        $parameters = array(
//
//            'Mobile1' => 999999990000,
//            'Code' => 'KiDytYtcfLobesxwwmbPTsXWYRPghEGJWauvLPCyMVcdfkIrBTXrsWkcrSmAZlCd'
//        );
//
//
//
//        Input::$json = (object)$parameters;
//        $response = $this->post('student@update', array());
//        var_dump($response);
//        $this->assertTrue(true);
//    }

//    public function testimport()
//    {
//        Auth::login(1);
//        $parameters = array(
//            'filePath' => 'tmp/1574bddb75c78a6fd2251d61e2993b5146201319-hLJAUSODyeREPgJBkozgibkYbdhyBfjLqehMNBYVZGfFMZlaOljvnItTFtTqmdEA.csv'
//        );
//
//        Input::$json = (object)$parameters;
//        $response = $this->post('student@post_upload', array());
//        var_dump($response);
//        $this->assertTrue(true);
//    }

    public function testGetStudents()
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
        $parameters = array(
            'classSection' => array('6-A')
        );

        Input::$json = (object)$parameters;
        $response = $this->post('student@getStudents', array());
        $this->assertEquals(200, $response->status());
        $this->assertEquals(1, count(json_decode($response->content, true)));

        $this->markTestIncomplete('Test for more filters.');
    }

    public function testGetStudentCodes()
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

        $parameters = array(
            'classSection' => array('6-A')
        );

        Input::$json = (object)$parameters;

        $response = $this->post('student@getStudentCodes', array());
        $this->assertEquals(200, $response->status());
        $this->assertEquals(1, count(json_decode($response->content, true)));

        $this->markTestIncomplete('Test for more filters.');
    }

    public function testGetStudentByCodes()
    {
        $user = $this->getSampleUser();
        Auth::login($user->id);

        $firstStudent = FactoryMuff::create('Student');
        $firstStudent->schoolId = $user->school()->first()->id;
        $firstStudent->code = 'code1';
        $firstStudent->save();

        $firstStudent = FactoryMuff::create('Student');
        $firstStudent->schoolId = $user->school()->first()->id;
        $firstStudent->code = 'code2';
        $firstStudent->save();

        $parameters = array(array('code1'));

        Input::$json = (object)$parameters;

        $response = $this->post('student@getStudentByCodes', array());
        $this->assertEquals(200, $response->status());
        $this->assertEquals(1, count(json_decode($response->content, true)));
    }

//    public function testimport()
//    {
//        Auth::login(1);
//        $parameters = array(
//            'class' => array('vi', 'x'),
//            'section' => array('a', 'b', 'e'),
//            'pageNumber'=>2
//        );
//        Input::$json = (object)$parameters;
//        $response = $this->post('student@getStudents', array());
//        var_dump($response);
//        $this->assertTrue(true);
//    }

}
