<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/16/13
 * Time: 10:51 AM
 * To change this template use File | Settings | File Templates.
 */
class School_Controller extends Base_Controller
{
    private $schoolRepo;
    private $smsRepo;
    private $studentRepo;
    private $teacherRepo;

    public function __construct()
    {
        parent::__construct();

        //add auth filter
        $this->filter('before', 'auth');

        $this->schoolRepo = new SchoolRepository();
        $this->smsRepo = new SMSRepository();
        $this->studentRepo = new StudentRepository();
        $this->teacherRepo = new TeacherRepository();

    }

    /**
     * Not used as of now
     * @return Laravel\Response
     */
    public function post_create()
    {
        $new_school = Input::json();

        if (empty($new_school)) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        $new_school->name = isset($new_school->name) ? $new_school->name : "";
        $new_school->address = isset($new_school->address) ? $new_school->address : NULL;
        $new_school->city = isset($new_school->city) ? $new_school->city : NULL;
        $new_school->state = isset($new_school->state) ? $new_school->state : NULL;
        $new_school->zip = isset($new_school->zip) ? $new_school->zip : NULL;
        $new_school->senderId = isset($new_school->sender_id) ? $new_school->sender_id : NULL;
        $new_school->contactPerson = isset($new_school->contact_person) ? $new_school->contact_person : NULL;
        $new_school->contactMobile = isset($new_school->contact_mobile) ? $new_school->contact_mobile : NULL;
        $new_school->code = Str::random(64, 'alpha');
        $result = $this->schoolRepo->createSchool($new_school);

        if (!empty($result)) {
            return Response::eloquent($result);
        } else {
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }
    }

    /**
     * Not used as of now
     * @return Laravel\Response
     */
    public function post_get()
    {
        $get_code = Input::json();
        if (empty($get_code))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $code = $get_code->code;

        try {
            $result = $this->schoolRepo->getSchool($code);
            if (empty($result))
                return Response::make(__('responseerror.not_found'), HTTPConstants::NOT_FOUND_ERROR_CODE);

        } catch (Exception $e) {
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }
        return Response::eloquent($result);
    }

    /**
     * Not used as of now
     * @return Laravel\Response
     */
    public function post_delete()
    {
        $delete_code = Input::Json();

        if (empty($delete_code))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $code = $delete_code->code;

        try {
            $result = $this->schoolRepo->deleteSchool($code);
            if ($result) {
                return Response::make(__('responseerror.delete_school'), HTTPConstants::SUCCESS_CODE);
            } else {
                return Response::make(__('responseerror.not_found'), HTTPConstants::NOT_FOUND_ERROR_CODE);
            }
        } catch (Exception $e) {
            Log::exception($e);
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }
    }

    public function action_post_update()
    {
        $update_data = Input::json();

        if (empty($update_data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $schoolId = Auth::user()->schoolId; //getting the loggined
        $school = School::find($schoolId); //getting school from the school id to obtain the school Code
        $updateData = array();
        if (isset($update_data->name))
            $updateData['name'] = $update_data->name;
        if (isset($update_data->address))
            $updateData['address'] = $update_data->address;
        if (isset($update_data->city))
            $updateData['city'] = $update_data->city;
        if (isset($update_data->state))
            $updateData['state'] = $update_data->state;
        if (isset($update_data->zip))
            $updateData['zip'] = $update_data->zip;
        if (isset($update_data->sender_id))
            $updateData['senderId'] = $update_data->sender_id;
        if (isset($update_data->contact_person))
            $updateData['contactPerson'] = $update_data->contact_person;
        if (isset($update_data->contact_mobile))
            $updateData['contactMobile'] = $update_data->contact_mobile;

        if ($school == NULL)
            return Response::make(__('responseerror.not_found'), HTTPConstants::NOT_FOUND_ERROR_CODE);
        try {
            $result = $this->schoolRepo->updateSchool($school->code, $updateData);
        } catch (InvalidArgumentException $ie) {
            Log::exception($ie);
            return Response::json(array('status' => false, 'message' => 'Please try again'), HTTPConstants::SUCCESS_CODE);
        }

        if (empty($result))
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);

        //command to create templates for the school
        Command::run(array('smstemplate', $schoolId));

        return Response::json(array('status' => true), HTTPConstants::SUCCESS_CODE);
    }

    public function action_get_classes()
    {
        return Response::json($this->schoolRepo->getClasses(Auth::user()->schoolId));
    }

    public function action_get_departments()
    {
        return Response::json($this->schoolRepo->getDepartments(Auth::user()->schoolId));
    }

    public function action_get_morning_routes($ignoreStudents = false, $ignoreTeachers = false)
    {
        $data = array();
        $user = Auth::user();
        $data1 = array();
        $data2 = array();
        $data3 = array();

        if (!$ignoreStudents)
            $data1 = array_merge($data, $this->schoolRepo->getMorningBusRoutes($user->schoolId));

        $data2 = $this->schoolRepo->getMorningBusRoutesOfTeachers($user->schoolId);
        if (!$ignoreTeachers)
            $data3 = array_merge($data1, $data2);

        $data = array_unique($data3);
        return Response::json(array_values($data));
    }

    public function action_get_evening_routes($ignoreStudents = false, $ignoreTeachers = false)
    {
        $data = array();
        $user = Auth::user();
        $data1 = array();
        $data2 = array();
        $data3 = array();
        if (!$ignoreStudents)
            $data1 = array_merge($data, $this->schoolRepo->getEveningBusRoutes($user->schoolId));

        $data2 = $this->schoolRepo->getEveningBusRoutesOfTeachers($user->schoolId);
        if (!$ignoreTeachers)
            $data3 = array_merge($data1, $data2);

        $data = array_unique($data3);
        return Response::json(array_values($data));
    }

    public function action_get_available_credits()
    {
        $creditsRemaining = $this->smsRepo->getRemainingCredits(Auth::user()->schoolId);
        return Response::json(intval($creditsRemaining));
    }

    public function action_get_available_templates()
    {
        return Response::json($this->schoolRepo->getSMSTemplates(Auth::user()->schoolId));
    }

    public function action_get_transactions_history()
    {
        $schoolId = Auth::user()->schoolId;
        $transactions = Transaction::where_schoolId($schoolId)->get();
        return Response::eloquent($transactions);
    }

    public function action_get_requested_templates_history()
    {
        $schoolId = Auth::user()->schoolId;
        $transactions = RequestedTemplate::where_schoolId($schoolId)->get();
        return Response::eloquent($transactions);
    }

    public function action_get_all_schools()
    {
        $schools = $this->schoolRepo->getAllSchools();
        $totalSchools = array();
        foreach ($schools as $school) {
            $users = School::find($school->id)->users;
            $email = 'No Email';
            if (!empty($users))
                $email = $users[0]->email;
            $row = array();
            $row['name'] = "$school->name-[$email]";
            $row['code'] = $school->code;
            $totalSchools[] = $row;
        }
        return Response::json($totalSchools);
    }

    public function action_get_students_or_teachers_from_bus_routes()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $morningBusRoutes = isset($data->morningBusRoutes) ? $data->morningBusRoutes : array();
        $eveningBusRoutes = isset($data->eveningBusRoutes) ? $data->eveningBusRoutes : array();
        $schoolId = Auth::user()->schoolId;
        $students = $this->studentRepo->getStudentFromBusRoutes($morningBusRoutes, $eveningBusRoutes, $schoolId);
        if ($students == false && !is_array($students))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        $teachers = $this->teacherRepo->getTeachersFromBusRoutes($morningBusRoutes, $eveningBusRoutes, $schoolId);
        if ($teachers == false && !is_array($teachers))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        $studentsData = array();
        foreach ($students as $student) {
            $row = array();
            $mobileCount = 0;
            if ($student->mobile1 != "")
                $mobileCount++;
            if ($student->mobile2 != "")
                $mobileCount++;
            if ($student->mobile3 != "")
                $mobileCount++;
            if ($student->mobile4 != "")
                $mobileCount++;
            if ($student->mobile5 != "")
                $mobileCount++;
            $row['code'] = $student->code;
            $row['name'] = $student->name;
            $row['classStandard'] = $student->classStandard;
            $row['classSection'] = $student->classSection;
            $row['mobileCount'] = $mobileCount;
            $studentsData[] = $row;
        }


        $teachersData = array();
        foreach ($teachers as $teacher) {
            $row = array();
            $mobileCount = 0;
            if ($teacher->mobile1 != "")
                $mobileCount++;
            if ($teacher->mobile2 != "")
                $mobileCount++;
            if ($teacher->mobile3 != "")
                $mobileCount++;
            if ($teacher->mobile4 != "")
                $mobileCount++;
            if ($teacher->mobile5 != "")
                $mobileCount++;

            $row['code'] = $teacher->code;
            $row['name'] = $teacher->name;
            $row['department'] = $teacher->department;
            $row['mobileCount'] = $mobileCount;
            $teachersData[] = $row;
        }
        $teacherAndStudentData = array('students' => $studentsData, 'teachers' => $teachersData);
        return Response::json($teacherAndStudentData);
    }

    public function action_get_api_data()
    {
        try {
            $schoolId = Auth::user()->schoolId;
            $school = $this->schoolRepo->getSchoolFromID($schoolId);
            if (empty($school))
                return array();
            return Response::eloquent($school);

        } catch (Exception $e) {
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }
    }



}
