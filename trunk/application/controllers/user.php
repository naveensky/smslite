<?php

class User_Controller extends Base_Controller
{
    private $userRepo;
    private $schoolRepo;
    private $appSmsRepo;


    public function __construct()
    {
        parent::__construct();

        //add auth filter
        $this->filter('before', 'auth')
            ->except(
            array('login', 'post_login', 'register', 'post_register'));

        $this->userRepo = new UserRepository();
        $this->schoolRepo = new SchoolRepository();
        $this->appSmsRepo = new AppSMSRepository();
    }

    public function action_login()
    {
        return View::make('user/login');
    }

    /**
     * Post function for logging in user
     * @return Laravel\Response - returns bad request code or success code
     */
    public function action_post_login()
    {
        $data = Input::json();

        //if input is empty, return bad request
        if (empty($data)) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        $credentials = array(
            'username' => $data->email,
            'password' => $data->password
        );

        if (Auth::attempt($credentials)) {
            //if logged in, return success code
            return Response::make(__('responseerror.login_success'), HTTPConstants::SUCCESS_CODE);
        } else
            return Response::make(__('responseerror.login_fail'), HTTPConstants::BAD_REQUEST_CODE);
    }

    public function action_logout()
    {
        Auth::logout();
        return Redirect::to('/');
    }

    public function action_register($step = 1)
    {
        switch ($step) {
            case 1:
                return View::make('user.register');
                break;
            case 2:
                return View::make('user.schoolInfo')->with('senderId', Config::get('sms.senderid'));
                break;
            case 3:
                return View::make('user.mobileVerify');
                break;
            case 4:
                return View::make('user.emailVerify');
                break;
            default:
                //show page 1
        }
    }

    public function action_post_register()
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
        $status = $this->appSmsRepo->createAppSms($user->mobile, $welcome_message, Config::get('sms.senderId'), $user->id);

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
        //todo: pending
        return "view for deactivation pending";
    }

    public function action_post_deactivate()
    {
        $userId = Auth::user()->id;
        $status = $this->userRepo->deactivate($userId);

        //if user in case not deactivated send bad request code
        if (!$status)
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        //if user is deactivated successfully send a message and email.
        $deactivation_message = __('smstemplate.deactivation_message');
        $this->appSmsRepo->createAppSms(Auth::user()->mobile, $deactivation_message, Config::get('sms.senderId'), $userId);

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

        $this->appSmsRepo->createAppSms($user->mobile, $restore_message, Config::get('sms.senderId'), $user->id);

        //fire user restore event
        Event::fire(ListenerConstants::APP_USER_RESTORE, array(Auth::user()->id));
        return Response::make(__('responseerror.activate_success'), HTTPConstants::SUCCESS_CODE);
    }

    public function action_delete()
    {
        return "pending";
    }

    public function action_post_delete()
    {
        $userId = Auth::user()->id;
        $status = $this->userRepo->deleted($userId);
        if (!$status)
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        //success deletion
        $deletion_message = __('smstemplate.deletion_message');
        $this->appSmsRepo->createAppSms(Auth::user()->mobile, $deletion_message, Config::get('sms.senderId'), $userId);

        //todo: send complete user here rather than ID
        Event::fire(ListenerConstants::APP_USER_DELETE, array(Auth::user()->id));
        return Response::make(__('responseerror.activate_success'), HTTPConstants::SUCCESS_CODE);
    }

    public function action_forgot_password()
    {
        //todo: complete view
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

        //fire respective events
        Event::fire(ListenerConstants::APP_USER_PASSWORD_FORGOT, array($user));
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

        $newPasswordMessage = __('smstemplate.new_password_message', array('code' => $data['password']));
        $this->appSmsRepo->createAppSms($data['user']->mobile, $newPasswordMessage, Config::get('sms.senderId'), $data['user']->id);
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

        //todo: pending
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
        $this->appSmsRepo->createAppSms($user->mobile, $password_reset_message, $senderId, $user->id);

        Event::fire(ListenerConstants::APP_USER_PASSWORD_RESET, array($user));
        return "view password successfully changed";
    }

    public function action_resend_sms()
    {
        $userId = Auth::user()->id;
        $user = $this->userRepo->getUser($userId);
        if ($user->mobileVerificationCode == NULL)
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $welcomeMessage = __('smstemplate.welcome_message', array('code' => $user->mobileVerificationCode));
        $this->appSmsRepo->createAppSms($user->mobile, $welcomeMessage, Config::get('sms.senderId'), $user->id);
        return Response::json(array('status' => true), HTTPConstants::SUCCESS_CODE);

    }

    public function action_verify_mobile()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $mobileActivationCode = isset($data->mobileActivationCode) ? $data->mobileActivationCode : "";

        if (empty($mobileActivationCode))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        try {
            $status = $this->userRepo->verifyMobile(Auth::user()->id, $mobileActivationCode);
        } catch (InvalidArgumentException $ie) {
            return Response::json(array('status' => false), HTTPConstants::SUCCESS_CODE);
        }

        if ($status) {
            return Response::json(array('status' => $status), HTTPConstants::SUCCESS_CODE);
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

        $userId = Auth::user()->id;

        try {
            $user = $this->userRepo->change_password($userId, $oldPassword, $newPassword);
        } catch (InvalidArgumentException $ie) {
            Log::exception($ie);
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }
        if (!empty($user)) {
            Event::first(ListenerConstants::APP_USER_PASSWORD_UPDATE, array($user));
            $passwordUpdateMessage = __('smstemplate.password_update_message');
            $this->appSmsRepo->createAppSms($user->mobile, $passwordUpdateMessage, Config::get('sms.senderId'), $user->id);
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
        if ($mobile == "")
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        $userId = Auth::user()->id;
        try {
            $user = $this->userRepo->updateMobile($userId, $mobile);
        } catch (InvalidArgumentException $ie) {
            Log::exception($ie);
            return Response::json(array('status' => false), HTTPConstants::SUCCESS_CODE);
        }
        if (!empty($user)) {
            $mobilePhoneUpdated = __('smstemplate.mobile_updated_message');
            $this->appSmsRepo->createAppSms($user->mobile, $mobilePhoneUpdated, Config::get('sms.senderId'), $user);
            return Response::json(array('status' => true), HTTPConstants::SUCCESS_CODE);
        }
        return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
    }

    public function action_resend_email()
    {
        $userId = Auth::user()->id;
        $user = $this->userRepo->getUser($userId);
        if ($user->emailVerificationCode == NULL)
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
//        $welcomeMessage = __('smstemplate.welcome_message', array('code' => $user->mobileVerificationCode));
//        $this->appSmsRepo->createAppSms($user->mobile, $welcomeMessage, Config::get('sms.senderId'), $user->id);
        //fire user created event
        Event::fire(ListenerConstants::APP_USER_CREATED, array($user));
        return Response::json(array('status' => true), HTTPConstants::SUCCESS_CODE);

    }

    public function action_update_email()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $email = isset($data->email) ? $data->email : "";
        if ($email == "")
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        $userId = Auth::user()->id;
        try {
            $user = $this->userRepo->updateEmail($userId, $email);
        } catch (InvalidArgumentException $ie) {
            Log::exception($ie);
            return Response::json(array('status' => false), HTTPConstants::SUCCESS_CODE);
        }
        if (!empty($user)) {
            Event::fire(ListenerConstants::APP_USER_EMAIL_UPDATE, array($user));
            return Response::json(array('status' => true), HTTPConstants::SUCCESS_CODE);
        }
        return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
    }
}
