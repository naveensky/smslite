<?php

class Home_Controller extends Base_Controller
{

    public function action_index()
    {
        return View::make('home.index');
    }

    public function action_post_upload()
    {
        //todo: to make array with following properties
        //todo: array(filename=>'',path=>'',status=>'');

        $input = Input::all();
        $rules = array(
            'list' => 'required|mimes:csv'
        );

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            //if validation fails return false and shows errors
            return Response::json(false);
        }

        $extension = File::extension($input['list']['name']);
        $directory = path('public') . 'tmp';
        $filename = sha1(Auth::user()->id) . '-' . Str::random(64, 'alpha') . ".{$extension}";
        $upload_success = Input::upload('list', $directory, $filename);
        if ($upload_success) {
            //return full path to the file upload by the user
            $url = URL::to_asset('tmp/' . $filename);
            echo $url;
        } else {
            return Response::json(false);
        }
    }


}