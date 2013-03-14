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


    public function testUpdateStudent()
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
            'Mobile1' => 999999990000,
            'Code' => $student->code
        );

        Input::$json = (object)$parameters;
        $response = $this->post('student@update', array());
        $this->assertEquals(200, $response->status());
    }

    public function testImportStudents()
    {
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->save();

        Auth::login($user->id);

        $studentsData = array(
            array('uniqueIdentifier' => '012345678', 'name' => 'student1', 'classStandard' => '6', 'classSection' => 'A',
                'sex' => 'Male', 'fatherName' => 'XYZ', 'motherName' => 'EFG',
                'email' => '123@gmail.com', 'mobile1' => '8858353353', 'mobile2' => '',
                'mobile3' => '', 'mobile4' => '', 'mobile5' => '', 'dob' => '02-01-91', 'morningBusRoute' => '405', 'eveningBusRoute' => '607'),
            array('uniqueIdentifier' => '012345678', 'name' => 'student2', 'classStandard' => '6', 'classSection' => 'A',
                'sex' => 'Male', 'fatherName' => 'XYZ', 'motherName' => 'EFG',
                'email' => '123@gmail.com', 'mobile1' => '8858353353', 'mobile2' => '',
                'mobile3' => '', 'mobile4' => '', 'mobile5' => '', 'dob' => '02-01-91', 'morningBusRoute' => '405', 'eveningBusRoute' => '607'),
            array('uniqueIdentifier' => '012345678', 'name' => 'student3', 'classStandard' => '6', 'classSection' => 'A',
                'sex' => 'Male', 'fatherName' => 'XYZ', 'motherName' => 'EFG',
                'email' => '123@gmail.com', 'mobile1' => '8858353353', 'mobile2' => '',
                'mobile3' => '', 'mobile4' => '', 'mobile5' => '', 'dob' => '02-01-91', 'morningBusRoute' => '405', 'eveningBusRoute' => '607'),
            array('uniqueIdentifier' => '012345678', 'name' => 'student4', 'classStandard' => '6', 'classSection' => 'A',
                'sex' => 'Male', 'fatherName' => 'XYZ', 'motherName' => 'EFG',
                'email' => '123@gmail.com', 'mobile1' => '8858353353', 'mobile2' => '',
                'mobile3' => '', 'mobile4' => '', 'mobile5' => '', 'dob' => '02-01-91', 'morningBusRoute' => '405', 'eveningBusRoute' => '607'),
            array('uniqueIdentifier' => '012345678', 'name' => 'student5', 'classStandard' => '6', 'classSection' => 'A',
                'sex' => 'Male', 'fatherName' => 'XYZ', 'motherName' => 'EFG',
                'email' => '123@gmail.com', 'mobile1' => '8858353353', 'mobile2' => '',
                'mobile3' => '', 'mobile4' => '', 'mobile5' => '', 'dob' => '02-01-91', 'morningBusRoute' => '405', 'eveningBusRoute' => '607'),
        );

        $csvData = "";
        $headerRow = "Admission Number,Full Name,Class Standard,Class Section,Gender,Father Name,Mother Name,Email,Mobile1,Mobile2,Mobile3,Mobile4,Mobile5,DOB,Morning Bus Route,Evening Bus Route \n";
        $csvData .= $headerRow;
        foreach ($studentsData as $data) {
            $dataRow = "";
            foreach ($data as $key => $value) {
                $dataRow .= "\"$value\",";
            }
            $dataRow = rtrim($dataRow, ",");
            $csvData .= "$dataRow \n";
        }
        $directory = path('public') . 'tmp/';
        File::put($directory . 'student_test_list.csv', $csvData);
        $parameters = array(
            'filePath' => 'tmp/student_test_list.csv'
        );
        Input::$json = (object)$parameters;
        $response = $this->post('student@post_upload', array());
        $this->assertEquals(200, $response->status());
        File::delete($directory . 'student_test_list.csv');
    }

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

        $parameters = array(
            'codes' => array('code1')
        );

        Input::$json = (object)$parameters;

        $response = $this->post('student@getStudentByCodes', array());
        $this->assertEquals(200, $response->status());
        $this->assertEquals(1, count(json_decode($response->content, true)));
    }

}
