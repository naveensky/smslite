<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/21/13
 * Time: 12:23 PM
 * To change this template use File | Settings | File Templates.
 */
class TestTeachercontroller extends ControllerTestCase
{


//    public function testCreate()
//    {
//
//
//        $teachers = array(
//            (object)array(
//                'Name' => 'Akhil',
//                'Email' => '1@1.com',
//                'Mobile1' => 93535395355,
//                'Mobile2' => 835858735357,
//                'Mobile3' => 64264246264,
//                'Mobile4' => 424764524654,
//                'Mobile5' => 223332424223,
//                'DOB' => '1991-02-01',
//                'Department' => 'Hindi',
//                'MorningBusRoute' => '203',
//                'EveningBusRoute' => '205',
//                'Sex' => 'M'
//            ),
//
//            (object)array(
//                'Name' => 'Lakshay',
//                'Email' => '1@1.com',
//                'Mobile1' => 93535395355,
//                'Mobile2' => 835858735357,
//                'Mobile3' => 64264246264,
//                'Mobile4' => 424764524654,
//                'Mobile5' => 223332424223,
//                'DOB' => '1991-02-01',
//                'Department' => 'Hindi',
//                'MorningBusRoute' => '203',
//                'EveningBusRoute' => '205',
//                'Sex' => 'M'
//            )
//
//        );
//
//       $schoolcode = "BWMaybxcxtSsqgXufGnHUxSjpwswfpFCmESgeEpeAwVkKoNdijoHOiYrjGKEQZeK";
//       $parameters = (object)array('teachers' => $teachers,
//           'schoolCode' => $schoolcode
//        );
//
//        $teacher_json = $parameters;
//        Input::$json = $teacher_json;
//        $response = $this->post('teacher@create', array());
//        var_dump($response);
//        $this->assertTrue(true);
//    }

//    public function testGet()
//    {
//        $parameters = array(
//
//            'code' => 'PtcdGWLmoakpuFJneCRUsYIBPLizGxZOkacMLJGDWTtoujWljrsHYeYVOvfjAdSq'
//
//        );
//
//        Input::$json = (object)$parameters;
//        var_dump((object)$parameters);
//        $response = $this->get('teacher@get');
//        var_dump($response);
//        $this->assertTrue(true);
//    }

//    public function testDelete()
//    {
//        $parameters = array(
//
//            'code' => 'PtcdGWLmoakpuFJneCRUsYIBPLizGxZOkacMLJGDWTtoujWljrsHYeYVOvfjAdSq'
//
//        );
//
//        Input::$json = (object)$parameters;
//        var_dump((object)$parameters);
//        $response = $this->post('teacher@delete',array());
//        var_dump($response);
//        $this->assertTrue(true);
//    }


//    public function testupdate()
//    {
//        $parameters = array(
//
//            'Mobile1' => 999999990000,
//            'Code' => 'UDSeFenZkZbbIOhmyXhwzhHEiLdlMEvWXrBbuMxZCOgMDJgRspoPumzUUikvsJHa'
//        );
//
//
//
//        Input::$json = (object)$parameters;
//        $response = $this->post('teacher@update', array());
//        var_dump($response);
//        $this->assertTrue(true);
//    }

//    public function testimportteacher()
//    {
//        Auth::login(1);
//        $parameters = array(
//            'filePath' => 'tmp/teacher-upload.csv'
//        );
//        Input::$json = (object)$parameters;
//        $response = $this->post('teacher@post_upload', array());
//        var_dump($response);
//        $this->assertTrue(true);
//    }

//    public function testimport()
//    {
//        Auth::login(1);
//        $parameters = array(
//            'department' => array('science','maths'),
//            'morningBusRoute' => array('647','205'),
//        );
//        Input::$json = (object)$parameters;
//        $response = $this->post('teacher@getTeachers', array());
//
//        var_dump($response);
//        $this->assertTrue(true);
//    }


}
