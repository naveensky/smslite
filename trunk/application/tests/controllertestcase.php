<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/16/13
 * Time: 12:44 PM
 * To change this template use File | Settings | File Templates.
 */

abstract class ControllerTestCase extends PHPUnit_Framework_TestCase
{
    public function call($destination, $parameters = array(), $method = 'GET')
    {
        \Laravel\Request::foundation()->setMethod($method);
        return \Laravel\Routing\Controller::call($destination, $parameters);
    }

    public function get($destination, $parameters = array())
    {
        return $this->call($destination, $parameters, 'GET');
    }

    public function post($destination, $post_data, $parameters = array())
    {
        $this->clean_request();
        \Laravel\Request::foundation()->request->add($post_data);
        return $this->call($destination, $parameters, 'POST');
    }

    private function clean_request()
    {
        $request = \Laravel\Request::foundation()->request;
        foreach ($request->keys() as $key) {
            $request->remove($key);
        }
    }
}