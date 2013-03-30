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

        //add auth filter
        $this->filter('before', 'auth');
        //add mobile verified check
        $this->filter('before', 'checkmobile');

        $this->studentRepo = new StudentRepository();
        $this->schoolRepo = new SchoolRepository();
    }

    public function action_list()
    {
        return View::make('student.list');
    }

    public function action_upload()
    {
        return View::make('student.upload');
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
        $result = Student::parseFromCSV($contents);

        $studentInserted = count($result['bulkStudents']);
        if (empty($result['bulkStudents'])) {
            $studentInserted = 0;
            return Response::json(array('numberOfStudentInserted' => $studentInserted, 'rowNumbersError' => $result['errorRows']));
        }
        try {
            $isInserted = $this->studentRepo->bulkStudentsInsert($result['bulkStudents']);
        } catch (PDOException $pdo) {
            log::exception($pdo);
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }
        if (!$isInserted)
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);

        return Response::json(array('numberOfStudentInserted' => $studentInserted, 'rowNumbersError' => $result['errorRows']));
    }

    public function action_get($code = NULL)
    {
        if (empty($code))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        try {
            $result = $this->studentRepo->getStudent($code);
            if (empty($result))
                return Response::make(__('responseerror.not_found'), HTTPConstants::NOT_FOUND_ERROR_CODE);


        } catch (Exception $e) {
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }
        return Response::eloquent($result);
    }


    public function action_delete($code = NULL)
    {
        if (empty($code))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

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
        if (isset($update_data->admissionNumber))
            $updateData['uniqueIdentifier'] = $update_data->admissionNumber;
        if (isset($update_data->ClassStandard))
            $updateData['classStandard'] = $update_data->ClassStandard;
        if (isset($update_data->ClassSection))
            $updateData['classSection'] = $update_data->ClassSection;
        if (isset($update_data->MorningBusRoute))
            $updateData['morningBusRoute'] = $update_data->MorningBusRoute;
        if (isset($update_data->EveningBusRoute))
            $updateData['eveningBusroute'] = $update_data->EveningBusRoute;
        if (isset($update_data->Gender))
            $updateData['gender'] = $update_data->Gender;
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

    public function action_getStudents()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $classSection = isset($data->classSection) ? $data->classSection : array();
        $morningBusRoutes = isset($data->morningBusRoute) ? $data->morningBusRoute : array();
        $eveningBusRoutes = isset($data->eveningBusRoute) ? $data->eveningBusRoute : array();
        $pageCount = isset($data->pageCount) ? $data->pageCount : AppConstants::PAGE_COUNT;
        $pageNumber = isset($data->pageNumber) ? $data->pageNumber : AppConstants::PAGE_DEFAULT;
        $skip = $pageCount * ($pageNumber - 1);
        $filterStudents = $this->studentRepo->getStudents(
            $classSection, $morningBusRoutes, $eveningBusRoutes, $pageCount, $skip);

        if ($filterStudents == false && !is_array($filterStudents))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        return Response::eloquent($filterStudents);
    }

    /**
     * Function returns codes for students in json format as per the filter provided
     *
     * @return Laravel\Response - Json array for student code found
     */
    public function action_getStudentCodes()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $classSection = isset($data->classSection) ? $data->classSection : array();
        $morningBusRoutes = isset($data->morningBusRoute) ? $data->morningBusRoute : array();
        $eveningBusRoutes = isset($data->eveningBusRoute) ? $data->eveningBusRoute : array();
        $pageCount = isset($data->pageCount) ? $data->pageCount : PHP_INT_MAX;
        $pageNumber = isset($data->pageNumber) ? $data->pageNumber : 0;
        $skip = $pageNumber > 0 ? $pageCount * ($pageNumber - 1) : 0;

        $filterStudents = $this->studentRepo->getStudents(
            $classSection, $morningBusRoutes, $eveningBusRoutes, $pageCount, $skip);

        if ($filterStudents == false && !is_array($filterStudents))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $codes = array();
        foreach ($filterStudents as $student) {
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
            $row['mobileCount'] = $mobileCount;
            $codes[] = $row;
        }
        return Response::json($codes, HTTPConstants::SUCCESS_CODE);
    }

    public function action_getStudentByCodes()
    {
        $data = Input::json();

        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        $studentCodes = isset($data->codes) ? $data->codes : array();
        if (empty($studentCodes) || count($studentCodes) == 0)
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        try {
            $students = $this->studentRepo->getStudentsFromCodes($studentCodes);
        } catch (Exception $e) {
            Log::exception($e);
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }

        return Response::eloquent($students);
    }


    public function action_exportStudent()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $classSection = isset($data->classSection) ? $data->classSection : array();
        $morningBusRoutes = isset($data->morningBusRoute) ? $data->morningBusRoute : array();
        $eveningBusRoutes = isset($data->eveningBusRoute) ? $data->eveningBusRoute : array();
        $students = $this->studentRepo->getStudentsToExport(
            $classSection, $morningBusRoutes, $eveningBusRoutes);

        if ($students == false && !is_array($students))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $studentsCSV = Student::parseToCSV($students);

        //todo: pending download

    }

    public function action_findStudentByNameOrMobileOrAdmissionNumber()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        $searchValue = isset($data->searchValue) ? $data->searchValue : '';
        if (empty($searchValue))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $schoolId = Auth::user()->schoolId;
        $students = $this->studentRepo->getStudentByNameOrMobileOrAdmissionNumber($schoolId, $searchValue);
        if ($students == false && !is_array($students))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $studentData = array();
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
            $row['name'] = $student->name;
            $row['mobile1'] = $student->mobile1;
            $row['mobile2'] = $student->mobile2;
            $row['mobile3'] = $student->mobile3;
            $row['mobile4'] = $student->mobile4;
            $row['mobile5'] = $student->mobile5;
            $row['admissionNumber'] = $student->uniqueIdentifier;
            $row['code'] = $student->code;
            $row['mobileCount'] = $mobileCount;
            $studentData[] = $row;
        }

        return Response::json($studentData);

    }

}
