<?php

class Home_Controller extends Base_Controller
{
    public function action_index()
    {
        return View::make('home.index');
    }

    public function action_dashboard()
    {
        //todo: work on dashboard
        return "Pending Dashboard Page";
    }

}