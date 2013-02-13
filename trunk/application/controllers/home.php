<?php

class Home_Controller extends Base_Controller
{

    public function action_index()
    {
        return View::make('home.index');
    }

    public function action_post_upload()
    {
        //todo: central function to upload all files
        //todo: returns false or http path for files uploaded
    }
}