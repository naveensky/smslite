<?php

abstract class ControllerTestCase extends PHPUnit_Framework_TestCase
{
    protected function setupBeforeTests()
    {
        //start the bundles
        \Laravel\Bundle::start('factorymuff');
        $this->createDatabase();
        $this->loadSession();
    }

    protected function tearDownAfterTests()
    {
        $this->removeSession();
        $output = shell_exec("php artisan migrate:reset --env=testing");
        DB::query('DROP TABLE laravel_migrations');
    }


    protected function loadSession()
    {
        \Session::started() or \Session::load();
    }

    protected function removeSession()
    {
        \Session::flush();
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

    protected function getSampleUser()
    {
        $school = FactoryMuff::create('School');
        $school->contactMobile = '1234567890';
        $school->name = 'test school';
        $school->address = 'dwarka';
        $school->city = 'new delhi';
        $school->state = 'delhi';
        $school->zip = '110018';
        $school->senderId = 'abcdef';
        $school->contactPerson = '';
        $school->code = Str::random(64, 'alpha');
        $school->save();

        $user = FactoryMuff::create('User');
        $user->schoolId = $school->id;
        $user->save();

        return $user;
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