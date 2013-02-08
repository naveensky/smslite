<?php

class User_Controller extends Base_Controller
{
    private $userRepo;
    private $schoolRepo;
    private $appSmsRepo;


    public function __construct()
    {
        parent::__construct();
        $this->userRepo = new UserRepository();
        $this->schoolRepo = new SchoolRepository();
        $this->appSmsRepo = new AppSMSRepository();
    }

    public function action_login()
    {
        return View::make('auth/login');
    }

    public function action_post_login()
    {
        $data = Input::json();
        if (empty($data)) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        $credentials = array(
            'username' => $data->email,
            'password' => $data->password
        );

        if (Auth::attempt($credentials)) {

            return Response::make(__('responseerror.login_success'), HTTPConstants::SUCCESS_CODE);
        } else
            return Response::make(__('responseerror.login_fail'), HTTPConstants::BAD_REQUEST_CODE);

    }

    public function action_logout()
    {
        Auth::logout();
        return Redirect::to('login');
    }

    public function action_post_create()
    {
        $data = Input::json();

        //return if empty
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $email = isset($data->email) ? $data->email : "";
        $password = isset($data->password) ? $data->password : "";
        $mobile = isset($data->mobile) ? $data->mobile : "";


        $school = $this->schoolRepo->createEmptySchool();
        if (!$school)
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);

        $schoolCode = $school->code;

        try {
            $user = $this->userRepo->createAdmin($email, $mobile, $password, $schoolCode);

        } catch (InvalidArgumentException $ie) {
            Log::exception($ie);
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }

        if (empty($user))
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);

        $role_status = $this->userRepo->addAdminUserRole($user->id);
        if ($role_status) {

            $welcome_message = __('smstemplate.welcome_message', array('code' => $user->mobileVerificationCode));

            $this->appSmsRepo->createAppSms($user->mobile, $welcome_message, Config::get('sms_config.senderId'), $user->id);

            $response = Event::first('welcome_email', array($user->id));

            //make the user as logged in user
            Auth::login($user->id);
            return Response::eloquent($user);
        }
    }

    public function action_activate($activationCode)
    {
        $activationCode = isset($activationCode) ? $activationCode : "";

        if (empty($activationCode))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);


        try {
            $status = $this->userRepo->activate($activationCode);
        } catch (InvalidArgumentException $ie) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        if ($status) {
            return Response::make(__('responseerror.activate_success'), HTTPConstants::SUCCESS_CODE);
        } else {
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }
    }

    public function action_deactivate()
    {
        $loggedinID = Auth::user()->id;
        if (empty($loggedinID))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        try {
            $user = $this->userRepo->deactivate($loggedinID);
        } catch (InvalidArgumentException $ie) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        if (!empty($user))
        {
            //todo:check how to set events and mail
//            $response = Event::first('welcome_email', array($user->id));
            $deactivation_message = __('smstemplate.deactivation_message');
            $this->appSmsRepo->createAppSms($user->mobile, $deactivation_message, Config::get('sms_config.senderId'), $user->id);
            return Response::make(__('responseerror.activate_success'), HTTPConstants::SUCCESS_CODE);

        }

        return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

    }

    public function action_delete()
    {
        $loggedinID = Auth::user()->id;
        if (empty($loggedinID))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        try {
            $status = $this->userRepo->deleted($loggedinID);
        } catch (InvalidArgumentException $ie) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        if (!empty($status))
            return Response::make(__('responseerror.activate_success'), HTTPConstants::SUCCESS_CODE);


        return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

    }

    public function action_forgotten_password()
    {
        $data = Input::json();
        $data->email = isset($data->email) ? $data->email : "";
        if (empty($data->email))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        try {
            $user = $this->userRepo->forgotten_password($data->email);
        } catch (InvalidArgumentException $ie) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }


        if (!empty($user)) {
            $forgotten_code = $user->forgotten_password_code;
            //todo:check how to set events
            $response = Event::first('welcome_email', array($user->id));
            return Response::eloquent($user);
        }

        return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
    }

    public function action_reset_password($code)
    {
        try {
            $user = $this->userRepo->forgotten_password_complete($code);
        } catch (InvalidArgumentException $ie) {
            Log::exception($ie);
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }

        if (empty($password))
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        //todo:check how to set events
        $response = Event::first('welcome_email', array($user->email));
        $password_reset_message = __('smstemplate.password_reset_successfully_message');
        $this->appSmsRepo->createAppSms($user->mobile, $password_reset_message, Config::get('sms_config.senderId'), $user->id);
        $new_password_message = __('smstemplate.new_password_message', array('code', $user->password));
        $this->appSmsRepo->createAppSms($user->mobile, $new_password_message, Config::get('sms_config.senderId'), $user->id);


    }

    public function action_resend_sms()
    {
        $loggedinID = Auth::user()->id;
        if ($loggedinID) {

            $user = $this->userRepo->getUser($loggedinID);
            if(is_null($user->mobileVerificationCode))
                return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

            $welcome_message = __('smstemplate.welcome_message', array('code' => $user->mobileVerificationCode));
            $this->appSmsRepo->createAppSms($user->mobile, $welcome_message, Config::get('sms_config.senderId'), $user->id);
            return Response::make(__('responseerror.resend_sms_success'), HTTPConstants::SUCCESS_CODE);

        } else
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
    }


    public function action_verify_mobile($mobileActivationCode)
    {

        $mobileActivationCode = isset($mobileActivationCode) ? $mobileActivationCode : "";

        if (empty($mobileActivationCode))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        try {
            $status = $this->userRepo->verifyMobile($mobileActivationCode);
        } catch (InvalidArgumentException $ie) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        if ($status) {
            return Response::make(__('responseerror.activate_success'), HTTPConstants::SUCCESS_CODE);
        } else {
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }
    }


    public function function_update_password()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $data->oldPassword = isset($data->oldPassword) ? $data->oldPassword : "";
        $data->newPassword = isset($data->newPassword) ? $data->newPassword : "";
        $loggedinID = Auth::user()->id;
        if ($loggedinID) {
            try {
                $this->userRepo->change_password($loggedinID, $data->oldPassword, $data->newPassword);
            } catch (InvalidArgumentException $ie) {
                Log::exception($ie);
                return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
            }
        }

    }

}