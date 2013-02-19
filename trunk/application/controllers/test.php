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
        var_dump(Hash::check('asdf', '$2a$08$dpLbsfeVq4TlIX4ja1X2U.1sYJwdeMedbJmIibdueBL.NnbjE9xdO'));
    }

    function action_test_mail()
    {
        Message::send(function ($message) {
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
        $obj = new MailServices();
        $obj->sendActivationEmail('hmalhotra@greenapplesolutions.com', '1313dsfshfsf');
    }

    public function action_checkstatus()
    {
        Auth::login(16);

        var_dump(Hash::check("password", Auth::user()->password));
    }


    public function action_testEvents()
    {
        $user = User::find(16);
        Event::fire('app.user_created', array($user));
    }

    public function action_id()
    {
        $userRoles = array('admin', 'editor');
        $ids = role::where_in('name', $userRoles)->get('id');
        var_dump($ids[0]->id);
    }

    public function action_csv()
    {
        $path = "http://smslite.localhost.com/tmp/1574bddb75c78a6fd2251d61e2993b5146201319-hLJAUSODyeREPgJBkozgibkYbdhyBfjLqehMNBYVZGfFMZlaOljvnItTFtTqmdEA.csv";
        $delimiter = ',';
        $enclosure = '"';

        if ($input = @fopen($path, 'r')) {

            $data = array();
            $rows = array();

            while ($fields = fgetcsv($input, 0, $delimiter, $enclosure)) {
                // spin headers...
                foreach ($fields as $field) {
                    // slug headers, blanks not allowed
                    $columns[] = Str::slug($field ? $field : uniqid(), '_');
                }


                $rows[] = array_combine($columns, $fields);


            }

            // close file
            fclose($input);

            // build object
            $class = __CLASS__;
            $object = new $class;
            $object->columns = $columns;
            $object->rows = $rows;
            var_dump($object);
        }

    }


    public function action_read()
    {
        $path = "http://smslite.localhost.com/tmp/1574bddb75c78a6fd2251d61e2993b5146201319-hLJAUSODyeREPgJBkozgibkYbdhyBfjLqehMNBYVZGfFMZlaOljvnItTFtTqmdEA.csv";
        $fp = fopen($path, "r");
        var_dump(fgets($fp));
        $csvData = '';
        while (!feof($fp)) {
            $row = fgets($fp);
            $dataRow = rtrim($row, ",");
            $csvData .= "$dataRow \r\n";
        }
        var_dump($csvData);
        fclose($fp);
    }

    public function action_teststring()
    {
        $path = path('public') . 'tmp';
        $contents = File::get($path . '/1574bddb75c78a6fd2251d61e2993b5146201319-hLJAUSODyeREPgJBkozgibkYbdhyBfjLqehMNBYVZGfFMZlaOljvnItTFtTqmdEA.csv');
        $contents = trim($contents);
        $student = new Student();
        $data = $student->parseFromCSV($contents);
        var_dump($data);

    }

    public function action_testLogin()
    {
        Auth::login(1);

        $schoolId = Auth::user()->schoolId;
        var_dump($schoolId);

    }

    public function action_test_filter()
    {
        Auth::login(1);
        $department = array('science', 'maths');
        $repo = new TeacherRepository();
        $morningBusRoute = array();
        $eveningBusRoute = array();
        $pageNo = 2;
        $pageCount = 1;
        $skip = $pageCount * ($pageNo - 1);
        $teachers = $repo->filterTeachers($department, $morningBusRoute, $eveningBusRoute, $pageCount, $skip);
        var_dump($teachers);

    }

    public function action_filterStudents()
    {
        Auth::login(1);
        $class = array('science', 'maths');
        $repo = new StudentRepository();
        $morningBusRoute = array();
        $eveningBusRoute = array();
        $classSections = array('6-A', '7-B');
        $students = $repo->getStudentsToExport($classSections, $morningBusRoute, $eveningBusRoute);
        Student::parseToCSV($students);

    }

    public function action_getClasses()
    {
        Auth::login(1);
        $repo = new StudentRepository();
        dd($repo->getClasses());

    }

    public function action_teacherlist()
    {
        Auth::login(1);
        $repo = new TeacherRepository();
        $data = $repo->getDepartments();
        dd($data);

    }

    public function action_testSMS()
    {
        Auth::login(1);
        $id = Auth::user()->id;
        $repo=new SMSRepository();
        $data=$repo->createSMS("Hello",array("AfXiqvOQBLLsYtzjgDobeHOfTWsJVMaxGBkUGmKZxdzKpVWJTrxXvJXCNXrpQEiC"),array(),"123",$id);
        var_dump($data);
    }

    public function action_count()
    {
        Auth::login(1);
        $repo = new StudentRepository();
        $data=$repo->getStudentCodeFromBusRoutes(array('70','77'),array('8887'));
        var_dump($data);


    }


}
