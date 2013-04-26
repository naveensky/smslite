<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Akhil
 * Date: 4/26/13
 * Time: 6:25 PM
 * To change this template use File | Settings | File Templates.
 */
class BlogController extends BaseController
{
    public function get10ThingsYouWillLoveMsngr()
    {
        return View::make('blog.10-reasons-youll-love-msngr');
    }
}
