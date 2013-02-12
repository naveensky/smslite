<?php

class Home_Controller extends Base_Controller
{

    /*
    |--------------------------------------------------------------------------
    | The Default Controller
    |--------------------------------------------------------------------------
    |
    | Instead of using RESTful routes and anonymous functions, you might wish
    | to use controllers to organize your application API. You'll love them.
    |
    | This controller responds to URIs beginning with "home", and it also
    | serves as the default controller for the application, meaning it
    | handles requests to the root of the application.
    |
    | You can respond to GET requests to "/home/profile" like so:
    |
    |		public function action_profile()
    |		{
    |			return "This is your profile!";
    |		}
    |
    | Any extra segments are passed to the method as parameters:
    |
    |		public function action_profile($id)
    |		{
    |			return "This is the profile for user {$id}.";
    |		}
    |
    */

    public function action_index()
    {
        return View::make('home.index');
    }


    public function action_post_upload()
    {
        $input = Input::all();
        $rules = array(
            'list' => 'required|mimes:csv'
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            //if validation fails return false and shows errors
            return false;
        }

        $extension = File::extension($input['list']['name']);
        $directory = path('public') . 'tmp';
//        $filename = sha1(16) . '-' . Str::random(64, 'alpha') . ".{$extension}";
        $filename = sha1(Auth::user()->id) . '-' . Str::random(64, 'alpha') . ".{$extension}";
        $upload_success = Input::upload('list', $directory, $filename);
        if ($upload_success) {
            //return full path to the file upload by the user
            $url = URL::to_asset('tmp/' . $filename);
            echo $url;
        } else {
            return false;
        }
    }


}