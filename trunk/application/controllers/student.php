<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/17/13
 * Time: 2:30 PM
 * To change this template use File | Settings | File Templates.
 */
class Student_Controller extends Base_Controller
{
    private $studentRepo;
    private $schoolRepo;

    public function __construct()
    {
        parent::__construct();
        $this->studentRepo = new StudentRepository();
        $this->schoolRepo=new SchoolRepository();

        //todo: add auth filter
    }

    public function action_upload(){
        //todo: view for upload of student
    }

    public function action_post_upload(){
        //todo: json input containing file path
        //todo: bulk upload for students
    }

    public function post_create()
    {
        $data = Input::json();

        if (empty($data) || count($data) == 0) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        $schoolCode = $data->schoolCode;

        $students = array();

        try {

            $checkSchoolCode = $this->schoolRepo->checkSchoolCode($schoolCode);
        } catch (InvalidArgumentException $ie) {
            Log::exception($ie);
            return Response::make(__('responseerror.not_found'), HTTPConstants::NOT_FOUND_ERROR_CODE);
        }

        foreach ($data->students as $new_student) {
            $student = new Student();
            $student->name = isset($new_student->Name) ? $new_student->Name : "";
            $student->email = isset($new_student->Email) ? $new_student->Email : NULL;
            $student->motherName = isset($new_student->MothersName) ? $new_student->MothersName : NULL;
            $student->fatherName = isset($new_student->FathersName) ? $new_student->FathersName : NULL;
            $student->mobile1 = isset($new_student->Mobile1) ? $new_student->Mobile1 : NULL;
            $student->mobile2 = isset($new_student->Mobile2) ? $new_student->Mobile2 : NULL;
            $student->mobile3 = isset($new_student->Mobile3) ? $new_student->Mobile3 : NULL;
            $student->mobile4 = isset($new_student->Mobile4) ? $new_student->Mobile4 : NULL;
            $student->mobile5 = isset($new_student->Mobile5) ? $new_student->Mobile5 : NULL;
            $student->dob = isset($new_student->DOB) ? $new_student->DOB : NULL;
            $student->classStandard = isset($new_student->ClassStandard) ? $new_student->ClassStandard : NULL;
            $student->classSection = isset($new_student->ClassSection) ? $new_student->ClassSection : NULL;
            $student->morningBusRoute = isset($new_student->MorningBusRoute) ? $new_student->MorningBusRoute : NULL;
            $student->eveningBusRoute = isset($new_student->EveningBusRoute) ? $new_student->EveningBusRoute : NULL;
            $student->sex = isset($new_student->Sex) ? $new_student->Sex : NULL;
            $student->code = Str::random(64, 'alpha');
            $students[] = $student;
        }

        try {

            $result = $this->studentRepo->createStudents($schoolCode, $students);
        } catch (InvalidArgumentException $ie) {
            Log::exception($ie);
            return Response::make(__('responseerror.not_found'), HTTPConstants::NOT_FOUND_ERROR_CODE);
        }


        if (empty($result) && count($result) < 0)
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
            $result = $this->studentRepo->getStudent($code);
            if (empty($result))
                return Response::make(__('responseerror.not_found'), HTTPConstants::NOT_FOUND_ERROR_CODE);


        } catch (Exception $e) {
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
            $result = $this->studentRepo->deleteStudent($code);
            if ($result) {
                return Response::make(__('responseerror.delete_student'), HTTPConstants::SUCCESS_CODE);
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
        if (isset($update_data->MothersName))
            $updateData['mothersName'] = $update_data->MothersName;
        if (isset($update_data->FathersName))
            $updateData['fathersName'] = $update_data->FathersName;
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
        if (isset($update_data->ClassStandard))
            $updateData['classStandard'] = $update_data->ClassStandard;
        if (isset($update_data->ClassSection))
            $updateData['classSection'] = $update_data->ClassSection;
        if (isset($update_data->MorningBusRoute))
            $updateData['morningBusRoute'] = $update_data->MorningBusRoute;
        if (isset($update_data->EveningBusRoute))
            $updateData['eveningBusroute'] = $update_data->EveningBusRoute;
        if (isset($update_data->Sex))
            $updateData['sex'] = $update_data->Sex;
        if (isset($update_data->Code))
            $studentCode = $update_data->Code;
        if (empty($studentCode))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        try {
            $result = $this->studentRepo->updateStudent($studentCode, $updateData);
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
