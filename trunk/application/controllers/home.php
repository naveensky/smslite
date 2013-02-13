<?php

class Home_Controller extends Base_Controller
{

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