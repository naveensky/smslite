<?php

abstract class ControllerTestCase extends PHPUnit_Framework_TestCase
{
    protected function setupBeforeTests()
    {
        //start the bundles
        \Laravel\Bundle::start('factorymuff');
        $this->createDatabase();
    }

    private function createDatabase()
    {
        // If there is not a declaration that migrations have been run'd
        shell_exec("php artisan migrate:install --env=testing");
        shell_exec("php artisan migrate:rebuild --env=testing");
    }

    protected function resetDatabase()
    {
        shell_exec("php artisan migrate:install --env=testing");
        shell_exec("php artisan migrate:rebuild --env=testing");
    }

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