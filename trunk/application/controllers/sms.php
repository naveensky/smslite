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
    public $restful = true;
    private $smsRepo;

    public function __construct()
    {
        parent::__construct();
        $this->smsRepo = new SMSRepository();
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
        try{
        $result = $this->smsRepo->createSMS($message, $studentCodes, $teacherCodes, $sender_id, $user_id);
        }
        catch(PDOException $e)
        {
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
