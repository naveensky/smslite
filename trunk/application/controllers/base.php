<?php

class Base_Controller extends Controller
{

    /**
     * Catch-all method for requests that can't be matched.
     *
     * @param  string    $method
     * @param  array     $parameters
     * @return Response
     */
    public function __construct()
    {
        parent::__construct();

        //create assets containers
        $this->createAssets();
    }

    public function __call($method, $parameters)
    {
        return Response::error('404');
    }

    private function createAssets()
    {
        if (Request::is_env('prod')) {
            Asset::add('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js');
            Asset::add('angular', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.0.4/angular.min.js', 'jquery');
            Asset::add('bootstrap', 'https://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/js/bootstrap.min.js', 'jquery');
            Asset::add('application', 'js/application.js', array('jquery', 'bootstrap', 'angular'));

            //add css
            Asset::add('bootstrap-css', 'https://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/css/bootstrap.no-icons.min.css');
            Asset::add('application-css', 'css/application.css');
        } else {

            //if request is not ajax, then run minify task
            if (!Request::ajax())
                Command::run(array('minify'));

            //add scripts
            Asset::add('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js');
            Asset::add('angular', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.0.4/angular.min.js', 'jquery');
            Asset::add('bootstrap', 'https://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/js/bootstrap.min.js', 'jquery');
            Asset::add('application', 'js/application.js', array('jquery', 'bootstrap', 'angular'));

            //add css
            Asset::add('bootstrap-css', 'https://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/css/bootstrap.no-icons.min.css');
            Asset::add('application-css', 'css/application.css');
        }
    }
}