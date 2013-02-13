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
            //todo: put all minified and combined scripts here
        } else {

            //add scripts
            Asset::add('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js');
            Asset::add('angular', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.0.4/angular.min.js', 'jquery');
            Asset::add('bootstrap', 'https://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/js/bootstrap.min.js', 'jquery');
            Asset::add('app', 'js/app.js', array('jquery', 'bootstrap', 'angular'));
            Asset::add('controller_user_login', 'js/controllers/user/login.js', array('jquery', 'bootstrap', 'angular', 'app'));

            //add css
            Asset::add('bootstrap-css', 'https://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/css/bootstrap.no-icons.min.css');
            Asset::add('theme-css', 'css/adminflare.min.css', array('bootstrap-css'));
            Asset::add('fonts-awesome', 'css/font-awesome.min.css', array('bootstrap-css'));
            Asset::add('fonts', 'css/fonts.css');
            Asset::add('fonts', 'css/app.css');
        }
    }
}