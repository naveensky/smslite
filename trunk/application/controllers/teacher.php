<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/21/13
 * Time: 11:43 AM
 * To change this template use File | Settings | File Templates.
 */
class Teacher_Controller extends Base_Controller
{

    private $teacherRepo;
    private $schoolRepo;

    public function __construct()
    {
        parent::__construct();
        $this->teacherRepo = new TeacherRepository();
        $this->schoolRepo = new SchoolRepository();

//        proceed ahead only if authenticated
        $this->filter('before', 'auth');
        //proceed ahead only if mobile is verified
        $this->filter('before', 'checkmobile');
    }

    public function action_list()
    {
        return View::make('teacher.list');
    }

    public function action_upload()
    {
        return View::make('teacher.upload');
    }

    public function action_post_upload()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $filePath = $data->filePath;
        $path = path('public');
        $contents = File::get($path . $filePath);
        //delete file after reading content
        File::delete($path . $filePath);
        $contents = trim($contents);
        $result = Teacher::parseFromCSV($contents);
        $teacherInserted = count($result['bulkTeachers']);
        if (empty($result['bulkTeachers'])) {
            $teacherInserted = 0;
            return Response::json(array('numberOfTeacherInserted' => $teacherInserted, 'rowNumbersError' => $result['errorRows']));
        }
        try {
            $this->teacherRepo->bulkTeachersInsert($result['bulkTeachers']);
        } catch (PDOException $pdo) {
            log::exception($pdo);
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }
        return Response::json(array('numberOfTeacherInserted' => $teacherInserted, 'rowNumbersError' => $result['errorRows']));
    }

    public function action_edit()
    {
        return View::make('teacher.edit');
    }

    public function action_post_get()
    {
        $data = Input::json();

        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        $codes = isset($data->codes) ? $data->codes : array();
        try {
            $teachers = $this->teacherRepo->getTeachersFromCodes($codes);
            if (empty($teachers))
                return Response::make(__('responseerror.not_found'), HTTPConstants::NOT_FOUND_ERROR_CODE);
        } catch (Exception $e) {
            Log::exception($e);
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }
        return Response::eloquent($teachers);
    }


    public function action_delete($code = NULL)
    {
        if (empty($code))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        try {

            $result = $this->teacherRepo->deleteTeacher($code);
            if ($result) {
                return Response::make(__('responseerror.delete_teacher'), HTTPConstants::SUCCESS_CODE);
            } else {
                return Response::make(__('responseerror.not_found'), HTTPConstants::NOT_FOUND_ERROR_CODE);
            }
        } catch (Exception $e) {
            Log::exception($e);
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }
    }


    public function action_update()
    {

        $update_data = Input::json();

        if (empty($update_data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $updateData = array();
        if (isset($update_data->Name))
            $updateData['name'] = $update_data->Name;
        if (isset($update_data->Email))
            $updateData['email'] = $update_data->Email;
        if (isset($update_data->Mobile1))
            $updateData['mobile1'] = $update_data->Mobile1;
        if (isset($update_data->Mobile2))
            $updateData['mobile2'] = $update_data->Mobile2;
        if (isset($update_data->Mobile3))
            $updateData['mobile3'] = $update_data->Mobile3;
        if (isset($update_data->Mobile4))
            $updateData['mobile4'] = $update_data->Mobile4;
        if (isset($update_data->Mobile5))
            $updateData['mobile5'] = $update_data->Mobile5;
        if (isset($update_data->DOB)) {
            if (!empty($update_data->DOB))
                $updateData['dob'] = new DateTime($update_data->DOB);
        }
        if (isset($update_data->Department))
            $updateData['department'] = $update_data->Department;
        if (isset($update_data->MorningBusRoute))
            $updateData['morningBusRoute'] = $update_data->MorningBusRoute;
        if (isset($update_data->EveningBusRoute))
            $updateData['eveningBusRoute'] = $update_data->EveningBusRoute;
        if (isset($update_data->Gender))
            $updateData['gender'] = $update_data->Gender;
        if (isset($update_data->Code))
            $teacherCode = $update_data->Code;

        if (empty($teacherCode))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        try {
            $result = $this->teacherRepo->updateTeacher($teacherCode, $updateData);
        } catch (InvalidArgumentException $ie) {
            Log::exception($ie);
            return Response::json(array('status' => false, 'message' => Lang::line('responseerror.not_found')->get()));
        }
        if (empty($result)) {
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }
        return Response::json(array('status' => true));
    }

    public function action_getTeachers()
    {

        $data = Input::json();

        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $departments = isset($data->departments) ? $data->departments : array();
        $morningBusRoutes = isset($data->morningBusRoutes) ? $data->morningBusRoutes : array();
        $eveningBusRoutes = isset($data->eveningBusRoutes) ? $data->eveningBusRoutes : array();
        $pageCount = isset($data->pageCount) ? $data->pageCount : 25;
        $pageNumber = isset($data->pageNumber) ? $data->pageNumber : 1;
        $skip = $pageCount * ($pageNumber - 1);
        $FilterTeachers = $this->teacherRepo->getTeachers(
            $departments, $morningBusRoutes, $eveningBusRoutes, $pageCount, $skip);

        if ($FilterTeachers == false && !is_array($FilterTeachers))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        return Response::eloquent($FilterTeachers);

    }

    public function action_exportTeachers()
    {

        $data = Input::json();

        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $departments = isset($data->departments) ? $data->departments : array();
        $morningBusRoutes = isset($data->morningBusRoutes) ? $data->morningBusRoutes : array();
        $eveningBusRoutes = isset($data->eveningBusRoutes) ? $data->eveningBusRoutes : array();
        $teachers = $this->teacherRepo->getTeachersToExport(
            $departments, $morningBusRoutes, $eveningBusRoutes);

        if ($teachers == false && !is_array($teachers))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $teachersCSV = Teacher::parseToCSV($teachers);

    }


    /**
     * Function returns codes for teachers and key map total mobile numbers in json format as per the filter provided
     *
     * @return Laravel\Response - Json array for teacher code and total mobile numbers credits found
     */
    public function action_getTeacherCodes()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $departments = isset($data->departments) ? $data->departments : array();
        $morningBusRoutes = isset($data->morningBusRoute) ? $data->morningBusRoute : array();
        $eveningBusRoutes = isset($data->eveningBusRoute) ? $data->eveningBusRoute : array();
        $pageCount = isset($data->pageCount) ? $data->pageCount : PHP_INT_MAX;
        $pageNumber = isset($data->pageNumber) ? $data->pageNumber : 0;
        $skip = $pageNumber > 0 ? $pageCount * ($pageNumber - 1) : 0;

        $FilterTeachers = $this->teacherRepo->getTeachers(
            $departments, $morningBusRoutes, $eveningBusRoutes, $pageCount, $skip);

        if ($FilterTeachers == false && !is_array($FilterTeachers))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $codes = array();
        foreach ($FilterTeachers as $teacher) {
            $code = array();
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

            $code['code'] = $teacher->code;
            $code['mobileCount'] = $mobileCount;
            $codes[] = $code;
        }

        return Response::json($codes);
    }

    public function action_findTeacherByNameOrMobile()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        $searchValue = isset($data->searchValue) ? $data->searchValue : '';

        if (empty($searchValue))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $schoolId = Auth::user()->schoolId;
        $teachers = $this->teacherRepo->getTeacherByNameOrMobile($schoolId, $searchValue);
        if ($teachers == false && !is_array($teachers))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $teacherData = array();
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
            $row['name'] = $teacher->name;
            $row['mobile1'] = $teacher->mobile1;
            $row['mobile2'] = $teacher->mobile2;
            $row['mobile3'] = $teacher->mobile3;
            $row['mobile4'] = $teacher->mobile4;
            $row['mobile5'] = $teacher->mobile5;
            $row['code'] = $teacher->code;
            $row['mobileCount'] = $mobileCount;
            $teacherData[] = $row;
        }
        return Response::json($teacherData);

    }

}
