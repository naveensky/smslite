<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 5/7/13
 * Time: 4:47 PM
 * To change this template use File | Settings | File Templates.
 */

class Sync_Controller extends Base_Controller
{

    private $schoolRepo;
    private $dataService;

    public function __construct()
    {
        parent::__construct();

        //add auth filter
        $this->filter('before', 'auth');

        $this->schoolRepo = new SchoolRepository();
        $this->dataService = new DataProviderService();
    }

    public function action_data()
    {
        return View::make('syncdata.sync');
    }


    public function action_post_save()
    {
        $data = Input::Json();

        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $apiKey = isset($data->APIKey) ? $data->APIKey : '';
        $studentAPIUrl = isset($data->studentAPIUrl) ? rtrim($data->studentAPIUrl, '/') : '';
        $teacherAPIUrl = isset($data->teacherAPIUrl) ? rtrim($data->teacherAPIUrl, '/') : '';
        $fetchStudent = isset($data->fetchStudents) ? $data->fetchStudents : '';
        $fetchTeacher = isset($data->fetchTeachers) ? $data->fetchTeachers : '';


        if (empty($apiKey) || empty($studentAPIUrl) || empty($teacherAPIUrl))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        if (empty($fetchStudent) && empty($fetchTeacher))
            return Response::json(array('validationError' => true));
        try {
            $schoolId = Auth::user()->schoolId;
            $data = array('apiKey' => $apiKey, 'studentAPIUrl' => $studentAPIUrl, 'teacherAPIUrl' => $teacherAPIUrl);
            $school = $this->schoolRepo->addApiData($schoolId, $data);
            $studentSyncStatus = array();
            $teacherSyncStatus = array();
            $studentURLError = false;
            $teacherURLError = false;
            if ($fetchStudent == true || $fetchStudent == 'true') {
                $studentSyncStatus = $this->dataService->getStudentsData($school->apiKey, $school->studentAPIUrl, $schoolId);

                if ($studentSyncStatus['status'] == false)
                    $studentURLError = true;
            }
            if ($fetchTeacher == true || $fetchTeacher == 'true') {
                $teacherSyncStatus = $this->dataService->getTeachersData($school->apiKey, $school->teacherAPIUrl, $schoolId);
                if ($teacherSyncStatus['status'] == false)
                    $teacherURLError = true;
            }
            return Response::json(array('validationError' => false, 'student' => array('fetchStudent' => $fetchStudent, 'urlError' => $studentURLError, 'syncStatus' => $studentSyncStatus), 'teacher' => array('fetchTeacher' => $fetchTeacher, 'urlError' => $teacherURLError, 'syncStatus' => $teacherSyncStatus)));
        } catch (InvalidArgumentException $e) {
            return Response::make(__('responseerror.invalid_api_key_error'), 403);
        }
        catch (\Httpful\Exception\ConnectionErrorException $e) {
            return Response::make(__('responseerror.error_while_connecting_to_server'), HTTPConstants::DATABASE_ERROR_CODE);
        }
        catch (Exception $e) {
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }
    }
}