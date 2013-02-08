<?php
/**
 * Created by JetBrains PhpStorm.
 * User: saxena.arunesh
 * Date: 11/23/12
 * Time: 11:20 AM
 * To change this template use File | Settings | File Templates.
 */
class Test_Controller extends Base_Controller
{
    function action_list_of_buckets()
    {
        echo '<pre>';
        print_r(S3::listBuckets(true));
        echo '<pre/>';
    }


    function action_get_bucket()
    {
        print_r(S3::getBucket("cswinners.text"));
    }

    function action_put_file()
    {
        $metaHeaders = array("title" => "Sample File");
        print_r(S3::putObjectFile("C:/Users/saxena.arunesh/Desktop/test.txt", "cswinners.text", "uploads/test.txt", S3::ACL_PUBLIC_READ, $metaHeaders, 'text/plain'));
    }

    function action_get_file_info()
    {
        print_r(S3::getObjectInfo("cswinners.text", "uploads/lo"));
    }

    function action_download_file()
    {
        $file = "http://s3-ap-southeast-1.amazonaws.com/cswinners.text/uploads/general.xlsx";
        header("Content-type: application/force-download");
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . "general.xlsx");
        readfile($file);
        exit;
//
//        echo '<a href="http://s3-ap-southeast-1.amazonaws.com/cswinners.text/uploads/test.text?AWSAccessKeyId=AKIAI25JRXEYGHC6RWFQ&Expires=1353666836&Signature=8eIwn%2FuIdTT5r9egXsX2m80UB98%3D">Download</a>';
//   exit;
    }

    function action_get_expired_link()
    {
        $link = S3::getAuthenticatedURL("cswinners.text", "uploads/test.text", 300);
        echo $link;
        echo '<a href="$link">Click</a>';
    }

    function action_pass()
    {
       var_dump(Hash::check('asdf','$2a$08$dpLbsfeVq4TlIX4ja1X2U.1sYJwdeMedbJmIibdueBL.NnbjE9xdO'));
    }

    function action_test_mail()
    {
        Message::send(function($message)
        {
            $message->to('hmalhotra@greenapplesolutions.com');
            $message->from('hmalhotra@greenapplesolutions.com', 'Bob Marley');

            $message->subject('Hello!');
            $message->body('Well hello Someone, how is it going?');
        });

    }

    public function action_random()
    {
        var_dump(mt_rand(100000, 999999));
    }

    public function action_testm()
    {
        $obj=new MailServices();
        $obj->sendActivationEmail('hmalhotra@greenapplesolutions.com','1313dsfshfsf');
    }

    public function action_checkstatus()
    {
        Auth::login(4);
        var_dump(Auth::user()->email);
    }

}
