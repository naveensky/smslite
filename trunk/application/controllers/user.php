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

            //fire user created event 
            Event::fire('app.user_created', array($user->id));

            //make the user as logged in user
            Auth::login($user->id);
            return Response::eloquent($user);
        }
    }

    public function action_activate($activationCode)
    {
        $activationCode = isset($activationCode) ? $activationCode : "";

        //todo: show error view rather than showing bad error
        if (empty($activationCode))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        try {
            $status = $this->userRepo->activate($activationCode);
        } catch (InvalidArgumentException $ie) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        //todo: show success page
        if ($status) {
            return Response::make(__('responseerror.activate_success'), HTTPConstants::SUCCESS_CODE);
        } else {
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }
    }

    public function action_deactivate()
    {
        //todo: this check will already happen on auth filter
        //todo: remove this
        $loggedinID = Auth::user()->id;
        if (empty($loggedinID))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
            
        try {
            //todo: return true false 
            $user = $this->userRepo->deactivate($loggedinID);
        } catch (InvalidArgumentException $ie) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        //todo: check if user was not deleted. In case not, send bad code error

        if (!empty($user)) {
            $deactivation_message = __('smstemplate.deactivation_message');
            $this->appSmsRepo->createAppSms($user->mobile, $deactivation_message, Config::get('sms_config.senderId'), $user->id);
            return Response::make(__('responseerror.activate_success'), HTTPConstants::SUCCESS_CODE);
        }

        //fire event for user deactivation
        Event::fire('app.user_deactivated', array(Auth::user()->id));

        return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
    }

    public function action_restore_account($reactivationCode)
    {

        $reactivationCode = isset($reactivationCode) ? $reactivationCode : "";

        //show view and not response code
        if (empty($reactivationCode))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        
        try {
            $user = $this->userRepo->restoreAccount($reactivationCode);
        } catch (InvalidArgumentException $ie) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        if ($user) {
            $restore_message = __('smstemplate.restore_message');
            $this->appSmsRepo->createAppSms($user->mobile, $restore_message, Config::get('sms_config.senderId'), $user->id);
            return Response::make(__('responseerror.activate_success'), HTTPConstants::SUCCESS_CODE);
        } else {
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }
        
        //todo: fire event for account restoration app.user_restore        
    }

    public function action_delete()
    {
        //todo: this will be taken care by auth filter
        $loggedinID = Auth::user()->id;
        if (empty($loggedinID))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);        
            
        try {
            //todo: return true false for deleted account
            $status = $this->userRepo->deleted($loggedinID);
        } catch (InvalidArgumentException $ie) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        if (!empty($status))
            return Response::make(__('responseerror.activate_success'), HTTPConstants::SUCCESS_CODE);

        return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        
        //todo: fire delete event app.user_delete
    }

    public function action_forgotten_password()
    {
        //todo: rename to action_post_forgot_password
        //todo: create action_forgot_password for view
        
        $data = Input::json();
        $email = isset($data->email) ? $data->email : "";
        
        if (empty($email))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        try {
            //todo: rename to setForgotActivationCode
            $user = $this->userRepo->forgotten_password($email);
        } catch (InvalidArgumentException $ie) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        if(empty($user))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        
        //todo: rename this property to forgot_password_code
        $forgotten_code = $user->forgotten_password_code;
        //todo:check how to set events
        
        //todo: fire event app.user_password_forgot
        $response = Event::first('welcome_email', array($user->id));
        return Response::eloquent($user);    
    }

    public function action_reset_password($code)
    {            
       try {
            $data = $this->userRepo->forgotten_password_complete($code);
        } catch (InvalidArgumentException $ie) {
            Log::exception($ie);
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }

        if (empty($data))
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        //todo:check how to set events
        $response = Event::first('welcome_email', array($data['user']->id));
        $password_reset_message = __('smstemplate.password_reset_successfully_message');
        $this->appSmsRepo->createAppSms($data['user']->mobile, $password_reset_message, Config::get('sms_config.senderId'), $data['user']->id);
        $new_password_message = __('smstemplate.new_password_message', array('code' => $data['password']));
        $this->appSmsRepo->createAppSms($data['user']->mobile, $new_password_message, Config::get('sms_config.senderId'), $data['user']->id);
        
        //fire event : app.user_password_reset
        
    }

    public function action_resend_sms()
    {
        $loggedinID = Auth::user()->id;
        if ($loggedinID) {

            $user = $this->userRepo->getUser($loggedinID);
            if ($user->mobileVerificationCode == NULL)
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


    public function action_update_password()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $oldPassword = isset($data->oldPassword) ? $data->oldPassword : "";
        $newPassword = isset($data->newPassword) ? $data->newPassword : "";

        $loggedinID = Auth::user()->id;

        if ($loggedinID) {
            try {
                $user = $this->userRepo->change_password($loggedinID, $oldPassword, $newPassword);
            } catch (InvalidArgumentException $ie) {
                Log::exception($ie);
                return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
            }
            if (!empty($user)) {
                //todo:check how to set events and email code
                $response = Event::first('welcome_email', array($data['user']->id));
                $password_update_message = __('smstemplate.password_update_message');
                $this->appSmsRepo->createAppSms($user->mobile, $password_update_message, Config::get('sms_config.senderId'), $user->id);
                return Response::make(__('responseerror.activate_success'), HTTPConstants::SUCCESS_CODE);
            }
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }
        return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
    }

    public function action_update_mobile()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        $mobile = isset($data->mobile) ? $data->mobile : "";
        $loggedinID = Auth::user()->id;

        if ($loggedinID) {
                $result=$this->userRepo->updateMobile($loggedinID,$mobile);
            if($result)
            {
                return Response::make(__('responseerror.activate_success'), HTTPConstants::SUCCESS_CODE);
            }
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }
        return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
    }

}
