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
    public $restful = true;
    private $teacherRepo;
    private $schoolRepo;

    public function __construct()
    {
        parent::__construct();
        $this->teacherRepo = new TeacherRepository();
        $this->schoolRepo = new SchoolRepository();
    }

    public function post_create()
    {
        $data = Input::json();

        if (empty($data) || count($data) == 0) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        $schoolCode = $data->schoolCode;

        $teachers = array();

        try {
            $checkSchoolCode = $this->schoolRepo->checkSchoolCode($schoolCode);
        } catch (InvalidArgumentException $ie) {
            Log::exception($ie);
            return Response::make(__('responseerror.not_found'), HTTPConstants::NOT_FOUND_ERROR_CODE);
        }

        foreach ($data->teachers as $new_teacher) {
            $teacher = new Teacher();
            $teacher->name = isset($new_teacher->Name) ? $new_teacher->Name : "";
            $teacher->email = isset($new_teacher->Email) ? $new_teacher->Email : NULL;
            $teacher->mobile1 = isset($new_teacher->Mobile1) ? $new_teacher->Mobile1 : NULL;
            $teacher->mobile2 = isset($new_teacher->Mobile2) ? $new_teacher->Mobile2 : NULL;
            $teacher->mobile3 = isset($new_teacher->Mobile3) ? $new_teacher->Mobile3 : NULL;
            $teacher->mobile4 = isset($new_teacher->Mobile4) ? $new_teacher->Mobile4 : NULL;
            $teacher->mobile5 = isset($new_teacher->Mobile5) ? $new_teacher->Mobile5 : NULL;
            $teacher->dob = isset($new_teacher->DOB) ? $new_teacher->DOB : NULL;
            $teacher->department = isset($new_teacher->Department) ? $new_teacher->Department : NULL;
            $teacher->morningBusRoute = isset($new_teacher->MorningBusRoute) ? $new_teacher->MorningBusRoute : NULL;
            $teacher->eveningBusRoute = isset($new_teacher->EveningBusRoute) ? $new_teacher->EveningBusRoute : NULL;
            $teacher->sex = isset($new_teacher->Sex) ? $new_teacher->Sex : NULL;
            $teacher->code = Str::random(64, 'alpha');
            $teachers[] = $teacher;
        }
        //find school code from the current login code
        try {
            $result = $this->teacherRepo->createTeachers($schoolCode, $teachers); //school code
        } catch (InvalidArgumentException $ie) {
            Log::exception($ie);
            return Response::make(__('responseerror.not_found'), HTTPConstants::NOT_FOUND_ERROR_CODE);
        }

        if (empty($result))
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);

        return Response::json($result);
    }


    public function get_get()
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


    public function post_delete()
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


    public function post_update()
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

}
