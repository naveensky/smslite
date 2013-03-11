<?php

class HomeController extends BaseController
{

    public function getHome()
    {
        return View::make('home');
    }

    public function getAbout()
    {
        return View::make('about');
    }

    public function getPricing()
    {
        return View::make('pricing');
    }

    public function getBuy()
    {
        return View::make('buy');
    }

    public function getContact()
    {
        return View::make('contact');
    }
}