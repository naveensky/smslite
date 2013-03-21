<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 21/3/13
 * Time: 5:18 PM
 * To change this template use File | Settings | File Templates.
 */

class Admin_Controller extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();

        //add auth filter
        $this->filter('before', 'auth');
        //proceed ahead if user is super admin
        $this->filter('before', 'superadmin');

    }


    public function action_allocate_credits()
    {
        return View::make('admin.allocatecredits');
    }


}