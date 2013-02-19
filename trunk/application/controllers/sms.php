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

    public function __construct()
    {
        parent::__construct();
        $this->smsRepo = new SMSRepository();
        $this->studentRepo = new StudentRepository();
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
        $senderId = "gaps";
        //calling function for creating key value pair for studentCode => message
        $studentCodes = $this->smsRepo->getFormattedMessage($studentCodes, $message);
        $result = $this->smsRepo->createSMS($studentCodes, array(), $senderId, $userId);
        if ($result == false && !is_array($result))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        return Response::json($result);

    }

    public function action_post_send_to_busroutes()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $morningBusRoutes = isset($data->morningBusRoutes) ? $data->morningBusRoutes : array();
        $eveningBusRoutes = isset($data->eveningBusRoutes) ? $data->eveningBusRoutes : array();
//        $teachers = isset($data->teachers) ? $data->teachers : false;
//        $students = isset($data->students) ? $data->students : false;
        $message = isset($data->message) ? $data->message : '';

//        if (empty($teachers) && empty($students))
//            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        if (empty($morningBusRoutes) && empty($eveningBusRoutes))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

//        if (!empty($teachers))
        $teachersCodes = $this->studentRepo->getTeacherCodeFromBusRoutes($morningBusRoutes, $eveningBusRoutes);

        $teachersCodes = $this->smsRepo->getFormattedMessageTeachers($teachersCodes, $message);

//        if (!empty($students))
        $studentCodes = $this->studentRepo->getStudentCodeFromBusRoutes($morningBusRoutes, $eveningBusRoutes);

        $studentCodes = $this->smsRepo->getFormattedMessage($studentCodes, $message);

//get current logined user id

        $userId = Auth::user()->id;
        $senderId = "gaps";
        $result = $this->smsRepo->createSMS($studentCodes, $teachersCodes, $senderId, $userId);
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
