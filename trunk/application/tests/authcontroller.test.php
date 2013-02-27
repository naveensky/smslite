<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/31/13
 * Time: 1:41 PM
 * To change this template use File | Settings | File Templates.
 */
class TestAuthcontroller extends PHPUnit_Framework_TestCase
{

    public static function setUpBeforeClass()
    {
        TestAuthcontroller::loadSession();
    }

    protected static function loadSession()
    {
        \Session::started() or \Session::load();
    }

    public function testSample()
    {
        $this->assertTrue(true);
    }


//    public function testlogin()
//    {
//        $data = array(
//            'email' => 'admin@admin.com',
//            'password' => 'password'
//
//        );
//
//        Input::$json = (object)$data;
//        Request::setMethod('POST');
//        $response = Controller::call('user@post_login');
//        var_dump($response);
//        $this->assertNotNull($response);
//        $this->assertEquals(200, $response->status());
//    }

//    public function testCreateUser()
//    {
//        Bundle::start('messages');
//        $data = array(
//            'email' => 'hitanshumalhotra@gmail.com',
//            'password' => 'password',
//            'mobile' => '95358938953'
//        );
//
//        Input::$json = (object)$data;
//        Request::setMethod('POST');
//        $response = Controller::call('user@post_signUp');
//        var_dump($response);
//        $this->assertNotNull($response);
//        $this->assertEquals(200, $response->status());
//
//    }

//    public function testActivation()
//    {
//        $data = array(
//            'code' => 'WcdeoFKIcESkIGCiVjCvkjyEpbAMUoitrcDOYQIHNMOsQRTKzSjqJgJFRExiFzeA',
//        );
////
////        Input::$json = (object)$data;
//
//
////
//        Request::setMethod('GET');
//        $response = Controller::call('user@activate', $data);
//        var_dump($response);
//    }

//    public function testDeactivation()
//    {
////        Input::$json = (object)$data;
////
//        Auth::login(16);
//        Request::setMethod('GET');
//        $response = Controller::call('user@deactivate', array());
//        var_dump($response);
//    }

//    public function testDelete()
//    {
//        $data = array(
//            'id' => 's+NC0N4z7QBK2zDCto86J5npj/XBxr8FAlwOzYIUGHUCh2lSPRvAK2SVFdGm4bV1B5JJE6jKLswxdK53lo8Z0Q==',
//        );
//
//        Input::$json = (object)$data;
////
//        Request::setMethod('POST');
//        $response = Controller::call('auth@delete',array());
//        var_dump($response);
//    }

//    public function testForgotten()
//    {
//        $data = array(
//            'email' => 'hitanshumalhotra@gmail.com'
//        );
//        Input::$json = (object)$data;
//        Request::setMethod('POST');
//        $response = Controller::call('user@post_forgot_password');
//        $this->assertNotNull($response);
//        $this->assertEquals(200, $response->status());
//    }

//    public function testforgottenComplete()
//    {
//        $data = array(
//            'code' => 'uqkRYwAvUzohfDaxcCglGiGMXIUyAHqKhJmOEFyGSMDQKnbCInzckmbUlQgCtefD',
//        );
//        Request::setMethod('GET');
//        $response = Controller::call('user@post_reset_password', $data);
//        var_dump($response);
//    }

//    public function testMobileVerify()
//    {
//        Auth::login(18);
//        $data = array(
//            'mobileActivationCode' => '200200',
//        );
////
////        Input::$json = (object)$data;
//
////
//        Request::setMethod('GET');
//        $response = Controller::call('user@verify_mobile', $data);
//        var_dump($response);
//        $this->assertEquals(200, $response->status());
//    }

//    public function testRestore()
//    {
//        $data = array(
//            'reactivationCode' => 'OXOrGnmPbXlxsbOTuUWGdgcmnIVQrmuDspRQHFUFzSAvdKkwfDtSLJNctPSQXosr',
//
//        );
//
//        Request::setMethod('GET');
//        $response = Controller::call('user@restore_account', $data);
//        var_dump($response);
//    }

//    public function testResendSms()
//    {
//        Auth::login(16);
//        Request::setMethod('GET');
//        $response = Controller::call('user@resend_sms', array());
//        var_dump($response);
//    }

//    public function testUpdatePassword()
//    {
//        Auth::login(16);
//        $data = array(
//            'oldPassword' => 'password',
//            'newPassword' => 'password'
//        );
//        Input::$json = (object)$data;
//
//        Request::setMethod('POST');
//        $response = Controller::call('user@update_password');
//
//        $this->assertNotNull($response);
//        $this->assertEquals(200, $response->status());
//    }

//    public function testUpdateMobile()
//    {
//        Auth::login(16);
//        $data = array(
//            'mobile' => '89684866846'
//        );
//        Input::$json = (object)$data;
//        Request::setMethod('POST');
//        $response = Controller::call('user@update_mobile');
//        var_dump($response);
//        $this->assertNotNull($response);
//        $this->assertEquals(200, $response->status());
//    }

//public function testsetPassword()
//{
//    $data = array(
//            'email' => 'hitanshumalhotra1@gmail.com',
//            'newPassword'=>'password'
//        );
//        Input::$json = (object)$data;
//        Request::setMethod('POST');
//        $response = Controller::call('user@post_set_password');
//        var_dump($response);
//        $this->assertNotNull($response);
//        $this->assertEquals(200, $response->status());
//}


}
