<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/16/13
 * Time: 10:51 AM
 * To change this template use File | Settings | File Templates.
 */
class School_Controller extends Base_Controller
{
    public $restful = true;
    private $schoolRepo;

    public function __construct()
    {
        parent::__construct();
        $this->schoolRepo = new SchoolRepository();
    }

    public function post_create()
    {
        $new_school = Input::json();

        if (empty($new_school)) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        $new_school->name = isset($new_school->name) ? $new_school->name : "";
        $new_school->address = isset($new_school->address) ? $new_school->address : NULL;
        $new_school->city = isset($new_school->city) ? $new_school->city : NULL;
        $new_school->state = isset($new_school->state) ? $new_school->state : NULL;
        $new_school->zip = isset($new_school->zip) ? $new_school->zip : NULL;
        $new_school->senderId = isset($new_school->sender_id) ? $new_school->sender_id : NULL;
        $new_school->contactPerson = isset($new_school->contact_person) ? $new_school->contact_person : NULL;
        $new_school->contactMobile = isset($new_school->contact_mobile) ? $new_school->contact_mobile : NULL;
        //todo: get current login user id
        $new_school->code = Str::random(64, 'alpha');
        $result = $this->schoolRepo->createSchool($new_school);

        if (!empty($result)) {
            return Response::eloquent($result);
        } else {
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }
    }

    public function post_get()
    {
        $get_code = Input::json();
        if (empty($get_code))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $code = $get_code->code;

        try {
            $result = $this->schoolRepo->getSchool($code);
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
            $result = $this->schoolRepo->deleteSchool($code);
            if ($result) {
                return Response::make(__('responseerror.delete_school'), HTTPConstants::SUCCESS_CODE);
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
        if (isset($update_data->name))
            $updateData['name'] = $update_data->name;
        if (isset($update_data->address))
            $updateData['address'] = $update_data->address;
        if (isset($update_data->city))
            $updateData['city'] = $update_data->city;
        if (isset($update_data->state))
            $updateData['state'] = $update_data->state;
        if (isset($update_data->zip))
            $updateData['zip'] = $update_data->zip;
        if (isset($update_data->sender_id))
            $updateData['senderId'] = $update_data->sender_id;
        if (isset($update_data->contact_person))
            $updateData['contactPerson'] = $update_data->contact_person;
        if (isset($update_data->contact_mobile))
            $updateData['contactMobile'] = $update_data->contact_mobile;
        if (isset($update_data->code))
            $schoolCode = $update_data->code;
        if (empty($schoolCode))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        try {
            $result = $this->schoolRepo->updateSchool($schoolCode, $updateData);
        } catch (InvalidArgumentException $ie) {
            Log::exception($ie);
            return Response::make(__('responseerror.not_found'), HTTPConstants::NOT_FOUND_ERROR_CODE);
        }

        if (empty($result))
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);

        return Response::Eloquent($result);
    }
}