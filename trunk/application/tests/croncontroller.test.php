<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/31/13
 * Time: 12:51 PM
 * To change this template use File | Settings | File Templates.
 */
class TestCroncontroller extends PHPUnit_Framework_TestCase
{
    public function testrunCron()
    {
        $response = Controller::call('cron@runCron', $parameters=array());
        var_dump($response);
    }

}
