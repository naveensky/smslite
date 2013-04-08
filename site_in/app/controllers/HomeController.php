<?php

class HomeController extends BaseController
{

    public $plans = array(
        array("id" => 1, "credits" => 10000, "price" => 7500),
        array("id" => 2, "credits" => 25000, "price" => 15000),
        array("id" => 3, "credits" => 50000, "price" => 25000),
        // array("id" => 4, "credits" => 80000, "price" => 50000),
        //array("id" => 5, "credits" => 200000, "price" => 100000),
    );

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
        return View::make('buy')->with('plans', $this->plans);
    }

    public function getPlan($id)
    {
        if (empty($id)) {
            $id = 1;
        }

        foreach ($this->plans as $plan) {

            if ($plan["id"] == $id) {
                $selected = $plan;
                break;
            }
        }


        if (empty($selected)) {
            $selected = $this->plans[0];
        }
        return View::make('plan')->with('plan', $selected)->with('plans', $this->plans);
    }

    public function getContact()
    {
        return View::make('contact');
    }
    public function getTerms()
    {
        return View::make('terms');
    }
    public function getPrivacy()
    {
        return View::make('privacy');
    }
}