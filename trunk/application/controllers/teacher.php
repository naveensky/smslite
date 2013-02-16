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

        //proceed ahead only if authenticated
//       $this->filter('before', 'auth');
    }

    public function action_list()
    {
        $departments = $this->teacherRepo->getDepartments();
        $morningBusRoutes = $this->teacherRepo->getMorningBusRoutes();
        $eveningBusRoutes = $this->teacherRepo->getEveningBusRoutes();
        $data['departments'] = $departments;
        $data['morningRoutes'] = $morningBusRoutes;
        $data['eveningRoutes'] = $eveningBusRoutes;
        return View::make('teacher.list', $data);
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

    public function action_get()
    {
        $get_code = Input::json();

        if (empty($get_code))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);


        $code = $get_code->code;

        try {
            $result = $this->teacherRepo->getTeacher($code);
            if (empty($result))
                return Response::make(__('responseerror.not_found'), HTTPConstants::NOT_FOUND_ERROR_CODE);
        } catch (Exception $e) {
            Log::exception($e);
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }
        return Response::eloquent($result);

    }


    public function action_post_delete()
    {
        $delete_code = Input::Json();

        if (empty($delete_code))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);


        $code = $delete_code->code;
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


    public function action_post_update()
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
        if (isset($update_data->DOB))
            $updateData['dob'] = $update_data->DOB;
        if (isset($update_data->Department))
            $updateData['department'] = $update_data->Department;
        if (isset($update_data->MorningBusRoute))
            $updateData['morningBusRoute'] = $update_data->MorningBusRoute;
        if (isset($update_data->EveningBusRoute))
            $updateData['eveningBusroute'] = $update_data->EveningBusRoute;
        if (isset($update_data->Sex))
            $updateData['sex'] = $update_data->Sex;
        if (isset($update_data->Code))
            $teacherCode = $update_data->Code;

        if (empty($teacherCode))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        try {
            $result = $this->teacherRepo->updateTeacher($teacherCode, $updateData);
        } catch (InvalidArgumentException $ie) {
            Log::exception($ie);
            return Response::make(__('responseerror.not_found'), HTTPConstants::NOT_FOUND_ERROR_CODE);
        }


        if (empty($result)) {
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);

        }
        return Response::Eloquent($result);

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
        $teachers=$this->teacherRepo->getTeachersToExport(
            $departments,$morningBusRoutes,$eveningBusRoutes);

        if ($teachers == false && !is_array($teachers))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $teachersCSV=Teacher::parseToCSV($teachers);

    }

}
