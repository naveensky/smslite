<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/24/13
 * Time: 3:49 PM
 * To change this template use File | Settings | File Templates.
 */
class TestSmscontroller extends ControllerTestCase
{
//    public function testCreateSms()
//    {
//        $studentCodes = array(
//            "GnhMctVgzEOIuydKaPtULVNZFvSTyvwvqOBSqmgTPQrMyKvCJkdmHiKCogSecZqX",
//            "mIEMgxNTlaZsKPBJFLnYpFNzwQNUrrzjrXUiBLRcfYwfzOTCWbVsqlOaMJSmKnZl"
//        );
//
//        $teacherCodes = array(
//            "vXwtfkYoNNExNqVmkxrewOtWDvoCpSRfOTjAFvmtVbgUgkqJBUjeRMpuKhZhgoTV",
//            "wXJaVeTMqNMbJXuESgjDiRyATlVpvFdUtquFLZHDWzhJIgxvvulBUBAlfmGoPzBH"
//        );
//
//        $message = "Dear parents, your child was absent today.";
//        $parameters = (object)array('studentCodes' => $studentCodes,
//            'teacherCodes' => $teacherCodes,
//            'message' => $message,
//            'user_id' => 2,
//            'sender_id' => 'ABCDEF'
//        );
//
//        Input::$json = $parameters;
//        $response = $this->post('SMS@create', array());
//        var_dump($response);
//        $this->assertTrue(true);
//    }


//    public function testaddsms()
//    {
////        Auth::login(1);
////        $parameters = array(
////            'classSections'=>array('8-A'),
////            'message'=>"Dear Parents, your child name"
////        );
////        Input::$json = (object)$parameters;
////        $response = $this->post('SMS@post_send_to_class', array());
////        var_dump($response);
//    }

    public function testbusroute()
    {
//        Auth::login(1);
//        $parameters = array(
//            'morningBusRoutes'=>array('70','77','647'),
//            'eveningBusRoutes'=>array('8887'),
//            'message'=>"Dear parents, teacher"
//        );
//        Input::$json = (object)$parameters;
//        $response = $this->post('SMS@post_send_to_busroutes', array());
//        var_dump($response);
    }

//    public function testDepartment()
//    {
//        Auth::login(1);
//        $parameters = array(
//            'department' => array('hindi','english'),
//            'message' => "Dear parents, teacher"
//        );
//        Input::$json = (object)$parameters;
//        $response = $this->post('SMS@post_send_to_department', array());
//        var_dump($response);
//
//    }
}
