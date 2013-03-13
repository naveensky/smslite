<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 11/2/13
 * Time: 4:19 PM
 * To change this template use File | Settings | File Templates.
 */

class Minify_Task
{

    public function run($arguments)
    {
        Bundle::start('compactor');

        $compactor = new Compactor();

        $cssPath = path('public') . "css/";
        $jsPath = path('public') . "js/";
        $jsControllers = path('public') . "js/controllers/";
        $jsServices = path('public') . "js/services/";
//        $jsIgnored=array("$jsPath.'application.js'");

        $cssFiles = array(
            $cssPath . 'fonts.css',
            $cssPath . 'adminflare.min.css',
            $cssPath . 'font-awesome.min.css',
            $cssPath . 'app.css',
        );

        $compactor->combine_files($cssFiles)->save_file($cssPath . "application.css");

        $compactor
            ->combine_directory($jsPath, array("application.js","modernizr-jquery.min.js"))
            ->combine_directory($jsServices)
//            ->combine_directory($jsControllers)
            ->combine_directory($jsControllers . "student/")
            ->combine_directory($jsControllers . "teacher/")
            ->combine_directory($jsControllers . "user/")
            ->combine_directory($jsControllers . "report/")
            ->combine_directory($jsControllers . "sms/")
            ->save_file($jsPath . "application.js");
    }
}