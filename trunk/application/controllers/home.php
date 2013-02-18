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
        $files = $input['files'];
//        $rules = array(
//            $input['files']['name'][0] => 'required|mimes:csv'
//        );
//        $validator = Validator::make($input, $rules);
//        if ($validator->fails()) {
//            //if validation fails return false and shows errors
//            return Response::json(false);
//        }
        $docs = array();
        foreach ($files as $key => $value)
            foreach ($value as $k => $v)
                $docs[$k][$key] = $v;

        foreach ($docs as $key => $doc) {
            $extension = File::extension($doc['name']);
            if($extension!='csv')
                return Response::json(false);

            $directory = path('public') . 'tmp/';
            $filename = sha1(Auth::user()->id) . '-' . Str::random(64, 'alpha') . ".{$extension}";
            //save the original
            $upload_success = File::put($directory . $filename, File::get($doc['tmp_name']));
            if ($upload_success) {
                //return full path to the file upload by the user
                $url = 'tmp/' . $filename;
                $uploadData = array(
                    'filename' => $doc['name'],
                    'path' => $url,
                    'status' => 'success'

                );
                return Response::json($uploadData);
            } else {
                return Response::json(false);
            }
        }
    }
}