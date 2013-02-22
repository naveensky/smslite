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
//            Asset::add('jquery-upload', 'js/jquery.upload.js', 'jquery');
//            Asset::add('app', 'js/app.js', array('jquery', 'bootstrap', 'angular','jquery-upload'));
//            Asset::add('controller_user_login', 'js/controllers/user/login.js', array('jquery', 'bootstrap', 'angular', 'app'));
//            Asset::add('controller_student_upload', 'js/controllers/student/upload.js', array('jquery', 'bootstrap', 'angular', 'app'));
//            Asset::add('controller_student_list', 'js/controllers/student/list.js', array('jquery', 'bootstrap', 'angular', 'app'));
//            Asset::add('controller_teacher_upload', 'js/controllers/teacher/upload.js', array('jquery', 'bootstrap', 'angular', 'app'));
//            Asset::add('controller_teacher_list', 'js/controllers/teacher/list.js', array('jquery', 'bootstrap', 'angular', 'app'));
//            Asset::add('controller_user_register', 'js/controllers/user/register.js', array('jquery', 'bootstrap', 'angular', 'app'));
//            Asset::add('school_service', 'js/services/student.js', array('jquery', 'bootstrap', 'angular', 'app'));
//            Asset::add('teacher_service', 'js/services/teacher.js', array('jquery', 'bootstrap', 'angular', 'app'));
//            Asset::add('controller_user_forgot_password', 'js/controllers/user/forgotpassword.js', array('jquery', 'bootstrap', 'angular', 'app'));
            //add css
            Asset::add('bootstrap-css', 'https://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/css/bootstrap.no-icons.min.css');
            Asset::add('application-css', 'css/application.css');
            Asset::add('app-css', 'css/app.css');

        }
    }
}