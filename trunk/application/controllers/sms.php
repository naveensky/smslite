<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/23/13
 * Time: 10:11 AM
 * To change this template use File | Settings | File Templates.
 */
class SMS_Controller extends Base_Controller
{

    private $smsRepo;
    private $studentRepo;
    private $teacherRepo;

    public function __construct()
    {
        parent::__construct();

        //add auth filter
        $this->filter('before', 'auth');

        $this->smsRepo = new SMSRepository();
        $this->studentRepo = new StudentRepository();
        $this->teacherRepo = new TeacherRepository();
    }

    public function action_compose()
    {
        return View::make('sms.compose');
    }

    public function action_send_to_class()
    {
        $this->studentRepo->getClasses();
        //todo:pending view for send to class
    }

    public function action_post_send_to_class()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $classSections = isset($data->classSections) ? $data->classSections : array();
        $message = isset($data->message) ? $data->message : '';

        if (count($classSections) == 0 || $message == '')
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $studentCodes = $this->studentRepo->getStudentCodes($classSections);

        if (empty($studentCodes))
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);

        //get current logined user id

        $userId = Auth::user()->id;
        $senderId = Config::get('sms.senderid'); //getting senderId from config file
        //calling function for creating key value pair for studentCode => message
        $studentCodes = $this->smsRepo->getFormattedMessage($studentCodes, $message);
        $result = $this->smsRepo->createSMS($studentCodes, array(), $senderId, $userId);
        if ($result == false && !is_array($result))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        return Response::json($result);

    }

    public function action_post_send_to_bus_routes()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        $morningBusRoutes = isset($data->morningBusRoutes) ? $data->morningBusRoutes : array();
        $eveningBusRoutes = isset($data->eveningBusRoutes) ? $data->eveningBusRoutes : array();
        $message = isset($data->message) ? $data->message : '';
        if (empty($morningBusRoutes) && empty($eveningBusRoutes))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        //getting teacher codes from the morning and evening bus routes
        $teachersCodes = $this->teacherRepo->getTeacherCodeFromBusRoutes($morningBusRoutes, $eveningBusRoutes);
        //getting key value pair for message and teacher code
        $teachersCodes = $this->smsRepo->getFormattedMessageTeachers($teachersCodes, $message);
        //getting students codes from the morning and evening bus routes
        $studentCodes = $this->studentRepo->getStudentCodeFromBusRoutes($morningBusRoutes, $eveningBusRoutes);
        //getting key value pair of message and student code
        $studentCodes = $this->smsRepo->getFormattedMessage($studentCodes, $message);
        //get current logined user id
        $userId = Auth::user()->id;
        $senderId = Config::get('sms.senderid'); //getting senderId from config file
        $result = $this->smsRepo->createSMS($studentCodes, $teachersCodes, $senderId, $userId);
        if ($result == false && !is_array($result))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        return Response::json($result);

    }

    public function action_post_send_to_department()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $department = isset($data->department) ? $data->department : array();
        $message = isset($data->message) ? $data->message : '';

        if (empty($department) || empty($message))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        //getting teacher Codes from the department
        $teacherCodes = $this->teacherRepo->getTeacherCodeFromDepartments($department);
        //getting key value pair for the teacherCode => message
        $teacherCodes = $this->smsRepo->getFormattedMessageDepartment($teacherCodes, $message);
        $userId = Auth::user()->id;
        $senderId = Config::get('sms.senderid'); //getting senderId from config file
        $result = $this->smsRepo->createSMS(array(), $teacherCodes, $senderId, $userId);
        if ($result == false && !is_array($result))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        return Response::json($result);
    }

    public function post_create()
    {
        $data = Input::json();
        if (empty($data) || count($data) == 0) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        if (empty($data->studentCodes) && empty($data->teachersCodes) && empty($data->message))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        if (empty($data->user_id))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $message = $data->message;
        $studentCodes = $data->studentCodes;
        $user_id = $data->user_id;
        $sender_id = isset($data->sender_id) ? $data->sender_id : "GAPS";
        $teacherCodes = $data->teacherCodes;
        try {
            $result = $this->smsRepo->createSMS($message, $studentCodes, $teacherCodes, $sender_id, $user_id);
        } catch (PDOException $e) {
            Log::exception($e);
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }
        if (empty($result))
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);

        return Response::make(__('responseerror.sms_transaction'), HTTPConstants::SUCCESS_CODE);
    }

    public function get_get()
    {
        $sms_code = Input::json();
        if (empty($sms_code))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $code = $sms_code->code;

        try {
            $result = $this->smsRepo->getSMS($code);
            if (empty($result))
                return Response::make(__('responseerror.not_found'), HTTPConstants::NOT_FOUND_ERROR_CODE);

        } catch (Exception $e) {
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }

        return Response::eloquent($result);
    }
}
