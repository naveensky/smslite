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

    public function action_signUp()
    {
        return View::make('auth/login');
    }

    public function action_post_signUp()
    {
        $data = Input::json();

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

        $welcome_message = __('smstemplate.welcome_message', array('code' => $user->mobileVerificationCode));
        $this->appSmsRepo->createAppSms($user->mobile, $welcome_message, Config::get('sms_config.senderId'), $user->id);
        //fire user created event
        Event::fire(ListenerConstants::APP_USER_CREATED, array($user));
        //make the user as logged in user
        Auth::login($user->id);
        return Response::eloquent($user);

    }

    public function action_activate($activationCode)
    {
        $activationCode = isset($activationCode) ? $activationCode : "";
        if (empty($activationCode))
            return "You have entered incorrect code view missing";
        try {
            $status = $this->userRepo->activate($activationCode);
        } catch (InvalidArgumentException $ie) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }
        if ($status) {
            return "Thank you for your email verification view missing";
        } else {
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }
    }

    public function action_deactivate()
    {

        return "view for deactivation pending";
    }

    public function action_post_deactivate()
    {
        $loggedinID = Auth::user()->id;
        $status = $this->userRepo->deactivate($loggedinID);
        //if user in case not deactivated send bad request code
        if (!$status)
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
//if user is deactivated successfully send a message and email.
        $deactivation_message = __('smstemplate.deactivation_message');
        $this->appSmsRepo->createAppSms(Auth::user()->mobile, $deactivation_message, Config::get('sms_config.senderId'), $loggedinID);
        //fire event for user deactivation
        Event::fire(ListenerConstants::APP_USER_DEACTIVATED, array(Auth::user()->id));
        return Response::make(__('responseerror.activate_success'), HTTPConstants::SUCCESS_CODE);
    }

    public function action_restore_account($reactivationCode)
    {
        $reactivationCode = isset($reactivationCode) ? $reactivationCode : "";
        //if empty reactivation code open the view showing invalid Reactivation Code
        if (empty($reactivationCode))
            return "view showing empty reactivation code";

        try {
            $user = $this->userRepo->restoreAccount($reactivationCode);
        } catch (InvalidArgumentException $ie) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        if (empty($user))
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);

        if ($user)
            $restore_message = __('smstemplate.restore_message');
        $this->appSmsRepo->createAppSms($user->mobile, $restore_message, Config::get('sms_config.senderId'), $user->id);
        Event::fire(ListenerConstants::APP_USER_RESTORE, array(Auth::user()->id));
        return Response::make(__('responseerror.activate_success'), HTTPConstants::SUCCESS_CODE);

    }

    public function action_delete()
    {
        return "delete confirmation View";
    }

    public function action_post_delete()
    {
        $loggedinID = Auth::user()->id;
        $status = $this->userRepo->deleted($loggedinID);
        if (!$status)
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        //success deletion
        $deletion_message = __('smstemplate.deletion_message');
        $this->appSmsRepo->createAppSms(Auth::user()->mobile, $deletion_message, Config::get('sms_config.senderId'), $loggedinID);
        Event::fire(ListenerConstants::APP_USER_DELETE, array(Auth::user()->id));
        return Response::make(__('responseerror.activate_success'), HTTPConstants::SUCCESS_CODE);
    }

    public function action_forgot_password()
    {

        return "forgot password view";
    }

    public function action_post_forgot_password()
    {
        $data = Input::json();
        $email = isset($data->email) ? $data->email : "";

        if (empty($email))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        try {
            $user = $this->userRepo->setForgotActivationCode($email);
        } catch (InvalidArgumentException $ie) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        if (empty($user))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        Event::first(ListenerConstants::APP_USER_PASSWORD_FORGOT, array($user));
        return Response::eloquent($user);
    }

    public function action_send_password_mobile()
    {
        $data = Input::json();
        if (empty($data)) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        $email = isset($data->email) ? $data->email : "";
        $mobile = isset($data->mobile) ? $data->mobile : "";

        try {
            $data = $this->userRepo->send_new_password_to_mobile($email, $mobile);
        } catch (InvalidArgumentException $ie) {
            log::exception($ie);
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        if (empty($data))
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        $new_password_message = __('smstemplate.new_password_message', array('code' => $data['password']));
        $this->appSmsRepo->createAppSms($data['user']->mobile, $new_password_message, Config::get('sms_config.senderId'), $data['user']->id);

    }


    public function action_post_reset_password($code)
    {
        try {
            $user = $this->userRepo->forgotten_password_complete($code);
        } catch (InvalidArgumentException $ie) {
            Log::exception($ie);
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        if (empty($user))
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);

        return "view pending for enter new password with passing user email in hidden field";
    }

    public function action_post_set_password()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $newPassword = isset($data->newPassword) ? $data->newPassword : "";
        $email = isset($data->email) ? $data->email : "";

        try {
            $user = $this->userRepo->setNewPassword($email, $newPassword);
        } catch (InvalidArgumentException $ie) {
            log::exception($ie);
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }
        if (empty($user))
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);

        $password_reset_message = __('smstemplate.password_reset_successfully_message');
        $senderId = Config::get('sms_config.sender_id');
        var_dump($senderId);
        $this->appSmsRepo->createAppSms($user->mobile, $password_reset_message, $senderId, $user->id);

        Event::first(ListenerConstants::APP_USER_PASSWORD_RESET, array($user));
        return "view password successfully changed";
    }

    public function action_resend_sms()
    {
        $loggedinID = Auth::user()->id;
        $user = $this->userRepo->getUser($loggedinID);
        if ($user->mobileVerificationCode == NULL)
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $welcome_message = __('smstemplate.welcome_message', array('code' => $user->mobileVerificationCode));
        $this->appSmsRepo->createAppSms($user->mobile, $welcome_message, Config::get('sms_config.senderId'), $user->id);
        return Response::make(__('responseerror.resend_sms_success'), HTTPConstants::SUCCESS_CODE);

    }


    public function action_verify_mobile($mobileActivationCode)
    {

        $mobileActivationCode = isset($mobileActivationCode) ? $mobileActivationCode : "";

        if (empty($mobileActivationCode))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        try {
            $status = $this->userRepo->verifyMobile(Auth::user()->id, $mobileActivationCode);
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

        try {
            $user = $this->userRepo->change_password($loggedinID, $oldPassword, $newPassword);
        } catch (InvalidArgumentException $ie) {
            Log::exception($ie);
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }
        if (!empty($user)) {
            Event::first(ListenerConstants::APP_USER_PASSWORD_UPDATE, array($user));
            $password_update_message = __('smstemplate.password_update_message');
            $this->appSmsRepo->createAppSms($user->mobile, $password_update_message, Config::get('sms_config.senderId'), $user->id);
            return Response::make(__('responseerror.activate_success'), HTTPConstants::SUCCESS_CODE);
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
        $result = $this->userRepo->updateMobile($loggedinID, $mobile);
        if ($result) {
            return Response::make(__('responseerror.activate_success'), HTTPConstants::SUCCESS_CODE);
        }
        return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);

    }

}
