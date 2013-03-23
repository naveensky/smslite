<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 2/19/13
 * Time: 10:48 AM
 * To change this template use File | Settings | File Templates.
 */
class File_Controller extends Base_Controller
{

    public function action_post_add()
    {
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

            if ($extension != 'csv') {
                return Response::json(
                    array('filename' => $doc['name'],
                        'path' => '',
                        'status' => 'fail',
                        'message' => "Filetype you are trying to upload is not allowed. Please upload valid file",
                    )
                );
            }
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
                return Response::json(
                    array('filename' => $doc['name'],
                        'path' => '',
                        'status' => 'fail',
                        'message' => "There is some internal error occured while uploading file please try again later.",
                    )
                );
            }
        }
    }

    public function post_delete()
    {
        //function to delete the file


    }

}
