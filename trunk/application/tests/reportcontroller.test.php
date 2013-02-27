<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 2/27/13
 * Time: 12:45 PM
 * To change this template use File | Settings | File Templates.
 */

class TestReportcontroller extends ControllerTestCase
{

    public function testgetSms()
    {

        Auth::login(1);
        $parameters = array(
            'classSections' => array(),
        );
        Input::$json = (object)$parameters;
        $response = $this->post('report@post_getSMS', array());
        var_dump($response);
        $this->assertTrue(true);
    }
}