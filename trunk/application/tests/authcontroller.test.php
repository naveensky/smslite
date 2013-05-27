<?php

require_once 'controllertestcase.php';

class TestAuthcontroller extends ControllerTestCase
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

    public function testLogin()
    {
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->password = Hash::make("asdf1234");
        $user->save();

        $parameters = array(
            'email' => $user->email,
            'password' => "asdf1234"
        );

        Input::$json = (object)$parameters;
        $response = $this->post('user@post_login', array());
        $this->assertEquals(200, $response->status());
    }


    public function testCreateUser()
    {
        Bundle::start('messages');
        $data = array(
            'email' => "exampledomain.org",
            'password' => '123456',
            'mobile' => '3855935358'
        );

        Input::$json = (object)$data;
        Request::setMethod('POST');
        $response = Controller::call('user@post_register');
        $this->assertEquals(200, $response->status());

    }

    public function testActivation()
    {
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->password = Hash::make("asdf1234");
        $user->emailVerificationCode = Str::random(64, 'alpha');
        $user->save();

        $data = array(
            'code' => $user->emailVerificationCode,
        );
        $response = $this->get('user@activate', $data);
        $this->assertEquals(302, $response->status());
    }

    public function testDeactivation()
    {
        Bundle::start('messages');
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->save();

        Auth::login($user->id);
        $response = $this->get('user@post_deactivate', array());
        $this->assertEquals(200, $response->status());
    }

    public function testDelete()
    {
        Bundle::start('messages');
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->save();

        Auth::login($user->id);
        $response = $this->get('user@post_delete', array());
        $this->assertEquals(200, $response->status());
    }

    public function testForgotten()
    {
        Bundle::start('messages');
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->save();

        $data = array(
            'email' => $user->email
        );
        Input::$json = (object)$data;
        Request::setMethod('POST');
        $response = Controller::call('user@post_forgot_password');
        $this->assertNotNull($response);
        $this->assertEquals(200, $response->status());
    }

    public function testForgottenByMobile()
    {
        Bundle::start('messages');
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->mobile = '9999999999';
        $user->save();

        $data = array(
            'mobile' => $user->mobile,
            'email' => $user->email
        );
        Input::$json = (object)$data;
        Request::setMethod('POST');
        $response = Controller::call('user@send_password_mobile');
        $this->assertNotNull($response);
        $this->assertEquals(200, $response->status());
    }

    public function testForgottenComplete()
    {
        Bundle::start('messages');
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->forgottenPasswordCode = Str::random(64, 'alpha');
        $user->save();
        $data = array(
            'code' => $user->forgottenPasswordCode,
        );
        $response = $this->get('user@reset_password', $data);
        $user = User::find($user->id);
        $this->assertEquals(302, $response->status());
        $this->assertNull($user->forgottenPasswordCode);

    }

    public function testMobileVerify()
    {
        Bundle::start('messages');
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->mobileVerificationCode = "123456";
        $user->mobile = '9999999999';
        $user->save();

        Auth::login($user->id);

        $data = array(
            'mobileActivationCode' => $user->mobileVerificationCode,
        );

        Input::$json = (object)$data;
        $response = $this->post('user@verify_mobile', array());
        $this->assertEquals(200, $response->status());
    }

    public function testRestore()
    {
        Bundle::start('messages');
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->mobileVerificationCode = "123456";
        $user->reactivateCode = Str::random(64, 'alpha');
        $user->isDeactivated = 1;
        $user->mobile = '9999999999';
        $user->save();

        $data = array(
            'reactivationCode' => $user->reactivateCode,
        );
        $response = $this->get('user@restore_account', $data);
        $this->assertEquals(200, $response->status());
    }

    public function testResendSMS()
    {
        $this->markTestSkipped(
            'Have to check resend sms test'
        );
        Bundle::start('messages');
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->mobileVerificationCode = "123456";
        $user->mobile = '9999999999';
        $user->save();

        Auth::login($user->id);

        Request::setMethod('GET');
        $response = Controller::call('user@resend_sms', array());
        $this->assertEquals(200, $response->status());
    }

    public function testUpdatePassword()
    {

        Bundle::start('messages');
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->mobileVerificationCode = "123456";
        $user->password = Hash::make("password");
        $user->mobile = '9999999999';

        $user->save();
        Auth::login($user->id);

        $data = array(
            'oldPassword' => "password",
            'newPassword' => 'asdf'
        );
        Input::$json = (object)$data;
        $response = $this->post('user@post_update_password', array());
        $this->assertNotNull($response);
        $this->assertEquals(200, $response->status());


    }

    public function testUpdateMobile()
    {
        Bundle::start('messages');
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->mobileVerificationCode = "123456";
        $user->password = Hash::make("password");
        $user->mobile = '9999999999';
        $user->save();

        Auth::login($user->id);
        $data = array(
            'mobile' => '89684866846'
        );
        Input::$json = (object)$data;
        $response = $this->post('user@update_mobile', array());
        $this->assertNotNull($response);
        $this->assertEquals(200, $response->status());
    }

    public function testSetPassword()
    {
        Bundle::start('messages');
        $school = FactoryMuff::create('School');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->mobileVerificationCode = "123456";
        $user->password = Hash::make("password");
        $user->mobile = '9999999999';
        $user->save();

        Session::put('id', $user->id);

        $data = array(
            'x_token' => Crypter::encrypt($user->email),
            'password' => 'password'
        );

        Input::$json = (object)$data;
        $response = $this->post('user@post_set_password', array());
        $this->assertNotNull($response);
        $this->assertEquals(200, $response->status());
    }


}
