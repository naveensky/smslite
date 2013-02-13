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

        $cssFiles = array(
            $cssPath . 'bootstrap.min.css',
            $cssPath . 'bootstrap-responsive.min.css',
            $cssPath . 'fonts.min.css',
            $cssPath . 'adminflare.min.css',
            $cssPath . 'font-awesome.min.css',
        );

        $compactor->combine_files($cssFiles)->save_file($cssPath . "css-combine.css");
        $compactor->combine_directory($jsControllers)->save_file($jsPath . "controllers.js");
    }
}