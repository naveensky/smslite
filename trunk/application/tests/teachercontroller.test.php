<?php

require_once 'controllertestcase.php';

class TestTeacherController extends ControllerTestCase
{
    public function setUp()
    {
        $this->setupBeforeTests();
    }


    public function testGetTeacher()
    {
        $user = $this->getSampleUser();
        Auth::login($user->id);

        $teacher = FactoryMuff::create('Teacher');
        $teacher->schoolId = $user->school()->first()->id;
        $teacher->department = "Hindi";
        $teacher->save();
        $parameters = array(
            'code' => $teacher->code
        );
        $response = $this->get('teacher@get', $parameters);
        $this->assertEquals(200, $response->status());
    }

    public function testDeleteTeacher()
    {
        $user = $this->getSampleUser();
        Auth::login($user->id);

        $teacher = FactoryMuff::create('Teacher');
        $teacher->schoolId = $user->school()->first()->id;
        $teacher->department = "Hindi";
        $teacher->save();
        $parameters = array(
            'code' => $teacher->code
        );
        $response = $this->get('teacher@delete', $parameters);
        $this->assertEquals(200, $response->status());
    }

    public function testUpdateTeacher()
    {
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->save();

        Auth::login($user->id);
        $teacher = FactoryMuff::create('Teacher');
        $teacher->schoolId = $user->school()->first()->id;
        $teacher->department = "Hindi";
        $teacher->save();

        $parameters = array(
            'Mobile1' => 999999990000,
            'Code' => $teacher->code
        );

        Input::$json = (object)$parameters;
        $response = $this->post('teacher@post_update', array());
        $this->assertEquals(200, $response->status());
    }

    public function testImportTeachers()
    {
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->save();

        Auth::login($user->id);

        $teachersData = array(
            array('name' => 'teacher1', 'department' => 'Hindi',
                'sex' => 'Male',
                'email' => '123@gmail.com', 'mobile1' => '8858353353', 'mobile2' => '',
                'mobile3' => '', 'mobile4' => '', 'mobile5' => '', 'dob' => '02-01-91', 'morningBusRoute' => '405', 'eveningBusRoute' => '607'),
            array('name' => 'teacher2', 'department' => 'Hindi',
                'sex' => 'Male',
                'email' => '123@gmail.com', 'mobile1' => '8858353353', 'mobile2' => '',
                'mobile3' => '', 'mobile4' => '', 'mobile5' => '', 'dob' => '02-01-91', 'morningBusRoute' => '405', 'eveningBusRoute' => '607'),
            array('name' => 'teacher3', 'department' => 'Hindi',
                'sex' => 'Male',
                'email' => '123@gmail.com', 'mobile1' => '8858353353', 'mobile2' => '',
                'mobile3' => '', 'mobile4' => '', 'mobile5' => '', 'dob' => '02-01-91', 'morningBusRoute' => '405', 'eveningBusRoute' => '607'),
            array('name' => 'teacher4', 'department' => 'Hindi',
                'sex' => 'Male',
                'email' => '123@gmail.com', 'mobile1' => '8858353353', 'mobile2' => '',
                'mobile3' => '', 'mobile4' => '', 'mobile5' => '', 'dob' => '02-01-91', 'morningBusRoute' => '405', 'eveningBusRoute' => '607'),
            array('name' => 'teacher5', 'department' => 'Hindi',
                'sex' => 'Male',
                'email' => '123@gmail.com', 'mobile1' => '8858353353', 'mobile2' => '',
                'mobile3' => '', 'mobile4' => '', 'mobile5' => '', 'dob' => '02-01-91', 'morningBusRoute' => '405', 'eveningBusRoute' => '607'),
        );

        $csvData = "";
        $headerRow = "Full Name,Department,Gender,Email,Mobile1,Mobile2,Mobile3,Mobile4,Mobile5,DOB,Morning Bus Route,Evening Bus Route \n";
        $csvData .= $headerRow;
        foreach ($teachersData as $data) {
            $dataRow = "";
            foreach ($data as $key => $value) {
                $dataRow .= "\"$value\",";
            }
            $dataRow = rtrim($dataRow, ",");
            $csvData .= "$dataRow \n";
        }
        $directory = path('public') . 'tmp/';
        File::put($directory . 'teacher_test_list.csv', $csvData);
        $parameters = array(
            'filePath' => 'tmp/teacher_test_list.csv'
        );
        Input::$json = (object)$parameters;
        $response = $this->post('teacher@post_upload', array());
        $this->assertEquals(200, $response->status());
        File::delete($directory . 'teacher_test_list.csv');
    }


    public function testGetTeachers()
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

        $parameters = array(
            'departments' => array('hindi')
        );
        Input::$json = (object)$parameters;
        $response = $this->post('teacher@getTeachers', array());
        $this->assertEquals(200, $response->status());
        $this->assertEquals(1, count(json_decode($response->content, true)));

    }




}
