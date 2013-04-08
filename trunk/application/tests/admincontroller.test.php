<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 25/3/13
 * Time: 3:51 PM
 * To change this template use File | Settings | File Templates.
 */
require_once 'controllertestcase.php';

class TestAdmincontroller extends ControllerTestCase
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

    public function testPostAllocateCredits()
    {
        Bundle::start('messages');
        $user = $this->getSampleUser();
        $school = $user->school()->first();
        $role = new Role(array('name' => 'superadmin'));
        $role->save();
        $user->roles()->attach($role->id);
        $smsCredit = FactoryMuff::create('SmsCredit');
        $smsCredit->schoolId = $school->id;
        $smsCredit->credits = 25;
        $smsCredit->save();

        Auth::login($user->id);
        $parameters = array(
            'school' => $school->code,
            'credits' => "1000",
            'amount' => "1000",
            'discount' => "",
            'remarks' => "Hello new credits allocated",
            'sender_id' => 'GAPS',
            'sendToSchool' => true
        );

        Input::$json = (object)$parameters;
        $response = $this->post('admin@post_allocate_credits', array());
        $this->assertEquals(200, $response->status());

    }

}