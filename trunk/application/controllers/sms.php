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
    private $messageParser;

    public function __construct()
    {
        parent::__construct();

        //add auth filter
        $this->filter('before', 'auth');

        $this->smsRepo = new SMSRepository();
        $this->studentRepo = new StudentRepository();
        $this->teacherRepo = new TeacherRepository();
        $this->messageParser = new MessageParser();
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

    /**
     * Add SMS to queue when received from UI
     * @return Laravel\Response
     */
    public function action_post_create()
    {
        $data = Input::json();
        if (empty($data) || count($data) == 0) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        $studentsCodes = isset($data->studentCodes) ? $data->studentCodes : array();
        $teachersCodes = isset($data->teacherCodes) ? $data->teacherCodes : array();
        $message = isset($data->message) ? $data->message : '';
        $templateId = isset($data->templateId) ? $data->templateId : NULL;
        $messageVars = isset($data->messageVars) ? $data->messageVars : array();

        if (empty($studentsCodes) && empty($teachersCodes))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        if (empty($message) && empty($templateId))
            return Response::json(array('status' => false, 'message' => __('responsemessages.empty_message')), HTTPConstants::SUCCESS_CODE);

        $userId = Auth::user()->id;
        $sender_id = isset($data->sender_id) ? $data->sender_id : Config::get('sms.senderid'); //getting senderId from config file;

        if (!empty($message) && empty($templateId)) {
            //calling function for creating key value pair for studentCode => message
            $studentMessages = array();
            $teacherMessages = array();

            foreach ($studentsCodes as $code) {
                $studentMessages[$code->code] = $message;
            }

            foreach ($teachersCodes as $code) {
                $teacherMessages[$code->code] = $message;
            }
        }

        if (empty($message) && !empty($templateId)) {
            $codesForStudents = array();
            $codesForTeachers = array();
            //only getting codes from studentCodes
            foreach ($studentsCodes as $studentCode) {
                $codesForStudents[] = $studentCode->code;
            }

            foreach ($teachersCodes as $teacherCode) {
                $codesForTeachers[] = $teacherCode;
            }

            $students = $this->studentRepo->getStudentsFromCodes($codesForStudents);
            $teachers = $this->teacherRepo->getTeachersFromCodes($codesForTeachers);

            $messages = $this->messageParser->parseTemplate($message, $students, $teachers, $messageVars);
            $studentMessages = $messages['studentCodes'];
            $teacherMessages = $messages['teacherCodes'];
        }

        //queue SMS
        try {
            $result = $this->smsRepo->createSMS($studentMessages, $teacherMessages, $sender_id, $userId);
        } catch (PDOException $e) {
            Log::exception($e);
            return Response::json(array('status' => false, 'message' => __('responsemessages.pdo_error_sms')), HTTPConstants::SUCCESS_CODE);
        }

        if ($result == false && !is_array($result))
            return Response::json(array('status' => false, 'message' => __('responsemessages.pdo_error_sms')), HTTPConstants::SUCCESS_CODE);


        return Response::json(array('status' => true, 'result' => $result));
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

    public function action_post_create_sms_from_template()
    {
        $data = Input::json();
        if (empty($data) || count($data) == 0) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        $studentsCodes = isset($data->studentCodes) ? $data->studentCodes : array();
        $teachersCodes = isset($data->teacherCodes) ? $data->teacherCodes : array();
        $message = isset($data->message) ? $data->message : '';
        $messageVars = isset($data->messageVars) ? $data->messageVars : array();
        $sender_id = isset($data->sender_id) ? $data->sender_id : Config::get('sms.senderid'); //getting senderId from config file;
        if (empty($studentsCodes) && empty($teachersCodes))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        if (empty($message))
            return Response::json(array('status' => false, 'message' => __('responsemessages.empty_message')), HTTPConstants::SUCCESS_CODE);

        $userId = Auth::user()->id;
        $students = $this->studentRepo->getStudentsFromCodes($studentsCodes);
        $teachers = $this->teacherRepo->getTeachersFromCodes($teachersCodes);

        $codes = $this->messageParser->parseTemplate($message, $students, $teachers, $messageVars);
        if (empty($codes))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $senderId = Config::get('sms.senderid'); //getting senderId from config file
        try {
            $result = $this->smsRepo->createSMS($codes['studentsCode'], $codes['teachersCode'], $sender_id, $userId);
        } catch (PDOException $e) {
            Log::exception($e);
            return Response::json(array('status' => false, 'message' => __('responsemessages.pdo_error_sms')), HTTPConstants::SUCCESS_CODE);
        }
        if ($result == false && !is_array($result))
            return Response::json(array('status' => false, 'message' => __('responsemessages.pdo_error_sms')), HTTPConstants::SUCCESS_CODE);

        return Response::json(array('status' => true, 'result' => $result));

    }

    public function action_post_get_template_message_vars()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        $templateId = isset($data->templateId) ? $data->templateId : "";
        if ($templateId == "")
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        $template = $this->smsRepo->getTemplate($templateId);
        if (empty($template))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        $messageVars = $this->messageParser->getVariables($template->body);
        return Response::json($messageVars);

    }

}
