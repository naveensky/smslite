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
        $repo = new SMSRepository();
        $studentRepo = new StudentRepository();
        $studentCodes = $studentRepo->getStudentCodes(array('8-A'));
        $studentCodes = $repo->getFormattedMessage($studentCodes, "hello123");
        $data = $repo->createSMS($studentCodes, array(), "123", $id);
        var_dump($data);
    }

    public function action_count()
    {
        Auth::login(1);
        $repo = new StudentRepository();
        $data = $repo->getStudentCodeFromBusRoutes(array('70', '77'), array('8887'));
        var_dump($data);

    }

    public function action_minify()
    {
        $jsPath = path('public') . "js/";
        echo $jsPath . "application.js";

    }

    public function action_join()
    {
        Auth::login(1);
        $classSections = array();
        $fromDate = new DateTime();
        $toDate = new DateTime();
//        $Repo = new ReportRepository();
        $query = DB::table('smsTransactions')
            ->left_join('students', 'smsTransactions.studentId', '=', 'students.id')
            ->left_join('teachers', 'smsTransactions.teacherId', '=', 'teachers.id');

        $studentRepo = new StudentRepository();
        $schoolId = Auth::user()->schoolId;
        $query = $query->where(function ($query) use ($schoolId) {
            $query->where("students.schoolId", '=', $schoolId);
            $query->or_where("teachers.schoolId", '=', $schoolId);
        });

        if (!empty($classSections)) {
            $count = 1; //count is used for making the query
            foreach ($classSections as $classSection) {
                $class = $studentRepo->getClass($classSection); //getting class from classSection
                $section = $studentRepo->getSection($classSection); //getting section from classSection
                if ($count == 1) {
                    $query = $query->where(function ($query) use ($class, $section) {
                        $query->where("classStandard", '=', $class);
                        $query->where("classSection", '=', $section);
                    });
                } else {
                    $query = $query->or_where(function ($query) use ($class, $section) {
                        $query->where("classStandard", '=', $class);
                        $query->where("classSection", '=', $section);
                    });
                }
                $count++;
            }
        }

        if ($fromDate != new DateTime()) {
            $fromDate = date('Y', $fromDate->getTimestamp()) . "-" . date('m', $fromDate->getTimestamp()) . "-" . date('d', $fromDate->getTimestamp()) . " 00:00:00";
            $fromDate = new DateTime($fromDate);
            $toDate = date('Y', $toDate->getTimestamp()) . "-" . date('m', $toDate->getTimestamp()) . "-" . date('d', $toDate->getTimestamp()) . " 00:00:00";
            $toDate = new DateTime($toDate);
            $toDate->add(new DateInterval('P1D'));
            $query = $query->where(function ($query) use ($fromDate, $toDate) {
                $query->where("smsTransactions.created_at", '>=', $fromDate);
                $query->where("smsTransactions.updated_at", '<', $toDate);
            });
        }
        $query = $query->get();
        var_dump(DB::last_query());
        var_dump($query);
    }

    public function action_searchByName()
    {
        Auth::login(1);
        $schoolId = Auth::user()->schoolId;
        $pageCount = AppConstants::PAGE_COUNT;
        $pageNumber = AppConstants::PAGE_DEFAULT;
        $skip = $pageCount * ($pageNumber - 1);
        $studentRepo = new StudentRepository();
        $result = $studentRepo->getStudentByNameOrMobile($schoolId, '9999999', $skip, $pageCount);
        var_dump($result);
    }

    public function action_test_template()
    {
        $subject = 'Dear Parents, <% text_teacher_name %> is asking text_ for a meet on <% text_PTM_date %>';
        $pattern = '/<%[^%>]*%>/';
        preg_match_all($pattern, $subject, $matches);
        $matches = $matches[0];
        $messageVars = array();
        foreach ($matches as $match) {
            $key = preg_replace(array('/<%/', '/%>/'), ' ', $match);
            $value = explode("_", $key);
            $value = ucfirst($value[1]) . ' ' . ucfirst($value[2]);
            $messageVars[trim($key)] = trim($value);
        }
        var_dump($messageVars);
        $output = preg_replace_callback(
            '/<%[^%>]*%>/',
            function ($match) {
                return str_replace('text_', '$text_', $match[0]);
            },
            $subject
        );
        var_dump($output);
        $subject = str_replace('text_', '$text_', $subject);
        $filePath = path('app') . 'views/templateview.blade.php';
        File::put($filePath, $subject);
        $completeMessage = View::make('templateview', $messageVars);
        var_dump($completeMessage->render());
//        File::delete($filePath);
    }

    public function action_test_relatedModels()
    {
        Bundle::start('factorymuff');

        //create sample school
        $school = new School();
        $school->name = "Sample School";
        $school->code = Str::random(64, 'alnum');
        $school->address = "Random Address";
        $school->city = "City";
        $school->zip = "Zip";
        $school->state = "Zip";
        $school->senderId = "SCHOOL";
        $school->contactPerson = "";
        $school->contactMobile = "";
        $school->save();

        $transaction1 = FactoryMuff::create('Transaction');
        $transaction1->schoolId = $school->id;
        $transaction1->orderId = Str::random(64, 'alnum');
        $transaction1->save();

        $transaction2 = FactoryMuff::create('Transaction');
        $transaction2->schoolId = $school->id;
        $transaction2->orderId = Str::random(64, 'alnum');
        $transaction2->save();

    }

    public function action_transaction()
    {
        $schoolId = Auth::user()->schoolId;
        $transactions = Transaction::where_schoolId(1)->get();
        return Response::eloquent($transactions);

    }

    public function action_checkauth()
    {
        var_dump(User::find(Auth::user()->id)->roles);
        var_dump(in_array("superadmin", User::find(Auth::user()->id)->roles));
        $emails = Config::get('app.system_alert_emails');
        var_dump($emails);
        var_dump(Request::ip());
        $users = School::find(1)->users;
        var_dump($users[0]->email);

    }

    public function action_testDate()
    {
        Auth::login(1);
        $schoolId = Auth::user()->schoolId;
        $dateWiseData = array();
        $toDate = new DateTime();
        for ($i = 0; $i < 30; $i++) {
            $dateWiseData[$toDate->format('Y-m-d')] = 0;
            $toDate = $this->getFromDate($toDate);
        }
        var_dump($dateWiseData);
//        $query = DB::table('smsTransactions')
//            ->join('users', 'smsTransactions.userId', '=', 'users.id')->where_schoolId($schoolId);
//        $query = $query->where(function ($query) use ($toDate) {
//            $query->where("smsTransactions.updated_at", '>=', $toDate);
//            $query->where("smsTransactions.updated_at", '<', $this->getToDate(new DateTime()));
//        });

//        $count = $query->group_by(DB::raw('"smsTransactions"."updated_at"::date'))->count('smsTransactions.id');
//        $query=DB::last_query();
//        var_dump($query);
        $fromDate = $this->getToDate(new DateTime())->format("Y-m-d");
        $count = DB::query('select "smsTransactions"."updated_at"::date,count("smsTransactions"."id") as countSMS from "smsTransactions" join "users" on "smsTransactions"."userId"="users"."id" where "schoolId" =' . $schoolId . ' and "smsTransactions"."updated_at" >= ' . '\'' . $toDate->format("Y-m-d") . '\' and "smsTransactions"."updated_at" < \'' . $fromDate . ' \' group by "smsTransactions"."updated_at"::date order by "smsTransactions"."updated_at"::date DESC');
        var_dump($count);
        foreach ($count as $row) {
            $dateWiseData[$row->updated_at] = $row->countsms;
        }
        var_dump($dateWiseData);
    }

    public function getFromDate($toDate)
    {
        if (empty($toDate))
            return $toDate;
        $dateid = date('Y', $toDate->getTimestamp()) . "-" . date('m', $toDate->getTimestamp()) . "-" . date('d', $toDate->getTimestamp()) . " 00:00:00";
        $toDate = new DateTime($dateid);
        $toDate->sub(new DateInterval('P1D'));
        return $toDate;
    }

    public function getToDate($toDate)
    {
        if (empty($toDate))
            return $toDate;
        $dateid = date('Y', $toDate->getTimestamp()) . "-" . date('m', $toDate->getTimestamp()) . "-" . date('d', $toDate->getTimestamp()) . " 00:00:00";
        $toDate = new DateTime($dateid);
        $toDate->add(new DateInterval('P1D'));
        return $toDate;
    }

    public function action_getReportSMS()
    {
        Auth::login(1);
        $report = new ReportRepository();
        var_dump($report->getLast30DaysSMS(Auth::user()->schoolId));
    }

    public function action_checkTime()
    {
        //command to create templates for the school
        Command::run(array('smsdispatcher'));
        Command::run(array('highprioritysmsdispatcher'));
    }

    public function action_testMail()
    {
        Auth::login(1);
        $user = Auth::user();
        var_dump($user->to_array());
    }
}
