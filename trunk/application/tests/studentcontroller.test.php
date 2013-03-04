<?php

require_once 'controllertestcase.php';

class TestStudentController extends ControllerTestCase
{

    public function setUp()
    {
        $this->setupBeforeTests();
//        $this->loadSession();
    }




//    public function testcreate()
//    {
//        $students = array(
//            (object)array(
//                'Name' => 'ramesh',
//                'Email' => '1@1.com',
//                'MothersName' => 'abcde',
//                'FathersName' => 'ghijkl',
//                'Mobile1' => 93535395355,
//                'Mobile2' => 835858735357,
//                'Mobile3' => 64264246264,
//                'Mobile4' => 424764524654,
//                'Mobile5' => 223332424223,
//                'DOB' => '1991-02-01',
//                'ClassStandard' => 'A',
//                'ClassSection' => 'IV',
//                'MorningBusRoute' => '203',
//                'EveningBusRoute' => '205',
//                'Sex' => 'M'
//            ),
//
//            (object)array(
//                'Name' => 'suresh',
//                'Email' => '1@1.com',
//                'MothersName' => 'abcde',
//                'FathersName' => 'ghijkl',
//                'Mobile1' => 93535395355,
//                'Mobile2' => 835858735357,
//                'Mobile3' => 64264246264,
//                'Mobile4' => 424764524654,
//                'Mobile5' => 223332424223,
//                'DOB' => '1991-02-01',
//                'ClassStandard' => 'A',
//                'ClassSection' => 'IV',
//                'MorningBusRoute' => '203',
//                'EveningBusRoute' => '205',
//                'Sex' => 'M'
//            )
//
//        );
//
//        $schoolcode = "BWMaybxcxtSsqgXufGnHUxSjpwswfpFCmESgeEpeAwVkKoNdijoHOiYrjGKEQZeK";
//        $parameters = (object)array('students' => $students,
//            'schoolCode' => $schoolcode
//        );
//
//
//        Input::$json = $parameters;
//        $response = $this->post('student@create', array());
//        var_dump($response);
//        $this->assertTrue(true);
//    }


//    public function testGet()
//    {
//        $parameters = array(
//
//            'code' => 'DPufwnfMHpStINWyblsMvGrrzmGEELxvOdtjJSebDASzKgyxHifLqzWVSdyKoIeY'
//
//        );
//
//        Input::$json = (object)$parameters;
//        var_dump((object)$parameters);
//        $response = $this->get('student@get');
//        var_dump($response);
//        $this->assertTrue(true);
//    }

//    public function testDelete()
//    {
//        $parameters = array(
//
//            'code' => 'DPufwnfMHpStINWyblsMvGrrzmGEELxvOdtjJSebDASzKgyxHifLqzWVSdyKoIeY'
//
//        );
//
//        Input::$json = (object)$parameters;
//        var_dump((object)$parameters);
//        $response = $this->post('student@delete',array());
//        var_dump($response);
//        $this->assertTrue(true);
//    }


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
