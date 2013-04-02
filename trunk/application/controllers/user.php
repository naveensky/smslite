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
                array(
                    'login', 'post_login', 'activate', 'register',
                    'post_register', 'forgot_password',
                    'post_forgot_password', 'send_password_mobile',
                    'password_reset_success', 'reset_password',
                    'invalid_code', 'post_set_password', 'restore_account'));
        //add mobile verified check
        $this->filter('before', 'checkmobile')->only(array('transaction_history', 'update_password', 'post_update_password', 'profile', 'get_user_profile'));

        $this->userRepo = new UserRepository();
        $this->schoolRepo = new SchoolRepository();
        $this->smsRepo = new SMSRepository();
    }

    public function action_login()
    {
        if (Auth::check()) {
            if (Request::ajax())
                return Redirect::to('/sms/compose');
            else
                return Redirect::to('/#/sms/compose');
        }
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
            $redirectURL = '/home/dashboard';
            return Response::json(
                array(
                    "status" => true,
                    "url" => URL::base()
                ));
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
        $user = Auth::user();
        //filter to check if email is already verified then redirect user to sms compose screen
        if ($step == 4 && $user->emailVerificationCode == null) {
            if (Request::ajax())
                return Redirect::to('/sms/compose');
            else
                return Redirect::to('/#/sms/compose');
        }
        //if user come directly to email verify screen then send him/her back to mobile verify screen if not verify his mobile
        if ($step == 4 && $user->isVerified == 0)
            $step = 3;

        switch ($step) {
            case 1:
                return View::make('user.register');
                break;
            case 2:
                return View::make('user.schoolinfo')->with('senderId', Config::get('sms.senderid'));
                break;
            case 3:
                return View::make('user.mobileverify');
                break;
            case 4:
                return View::make('user.emailverify');
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
        $isValidEmail = $this->userRepo->validateEmail($email);
        if (!$isValidEmail)
            return Response::json(array('status' => false, 'message' => __('responsemessages.email_used')), HTTPConstants::SUCCESS_CODE);

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
        $status = $this->smsRepo->createAppSms($user->mobile, $welcome_message, Config::get('sms.senderid'), $user->id);

        //fire user created event
        Event::fire(ListenerConstants::APP_USER_CREATED, array($user));
        //make the user as logged in user
        Auth::login($user->id);
        return Response::json(array('status' => true), HTTPConstants::SUCCESS_CODE);
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
        $this->smsRepo->createAppSms(Auth::user()->mobile, $deactivation_message, Config::get('sms.senderid'), $userId);

        //fire event for user deactivation
        Event::fire(ListenerConstants::APP_USER_DEACTIVATED, array(User::find($userId)));
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


        $restore_message = __('smstemplate.restore_message');
        $this->smsRepo->createAppSms($user->mobile, $restore_message, Config::get('sms.senderid'), $user->id);
        //fire user restore event
        Event::fire(ListenerConstants::APP_USER_RESTORE, array($user));
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
        $this->smsRepo->createAppSms(Auth::user()->mobile, $deletion_message, Config::get('sms.senderid'), $userId);

        //todo: send complete user here rather than ID
        Event::fire(ListenerConstants::APP_USER_DELETE, array(User::find($userId)));
        return Response::make(__('responseerror.activate_success'), HTTPConstants::SUCCESS_CODE);
    }

    public function action_forgot_password()
    {
        return View::make('user.forgotPassword');
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
            return Response::json(array('status' => false, 'message' => __('responsemessages.forgot_password_by_email_error')), HTTPConstants::SUCCESS_CODE);
        }

        if (empty($user))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        //fire respective events
        Event::fire(ListenerConstants::APP_USER_PASSWORD_FORGOT, array($user));
        return Response::json(array('status' => true, 'message' => __('responsemessages.forgot_password_by_email_success')), HTTPConstants::SUCCESS_CODE);
    }

    public function action_send_password_mobile()
    {
        $data = Input::json();
        if (empty($data)) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        $email = isset($data->email) ? $data->email : "";
        $mobile = isset($data->mobile) ? $data->mobile : "";

        if (empty($email) || empty($mobile))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        try {
            $data = $this->userRepo->send_new_password_to_mobile($email, $mobile);
        } catch (InvalidArgumentException $ie) {
            Log::exception($ie);
            return Response::json(array('status' => false, 'message' => __('responsemessages.forgot_password_by_mobile_error')), HTTPConstants::SUCCESS_CODE);
        }

        if (empty($data))
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);

        $newPasswordMessage = __('smstemplate.new_password_message', array('code' => $data['password']));
        $this->smsRepo->createAppSms($data['user']->mobile, $newPasswordMessage, Config::get('sms.senderid'), $data['user']->id);
        return Response::json(array('status' => true, 'message' => __('responsemessages.forgot_password_by_mobile_success')), HTTPConstants::SUCCESS_CODE);
    }

    public function action_reset_password($code = null)
    {
        //return invalid code view if empty code found
        if (empty($code))
            return Redirect::to('/#/user/invalid_code');
        try {
            $user = $this->userRepo->forgotten_password_complete($code);
        } catch (InvalidArgumentException $ie) {
            Log::exception($ie);
            return Redirect::to('/#/user/invalid_code');
        }
        if (empty($user))
            return Redirect::to('/#/user/invalid_code');
        $email = $user[0]->email; //getting email to be sent as hidden field
        //encrypting email
        $email = Crypter::encrypt($email);
        Session::put('email', $email);
        Session::put('id', $user[0]->id);
        return Redirect::to('/#/user/password_reset_success');
    }

    public function action_password_reset_success()
    {
        $email = Session::get('email');
        return View::make('user.changepassword')->with('email', $email); //encrypted email id passed as data to view
    }

    public function action_invalid_code()
    {
        return View::make('error.404');
    }

    public function action_post_set_password()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $newPassword = isset($data->password) ? $data->password : "";
        $x_token = isset($data->x_token) ? $data->x_token : "";

        if (empty($newPassword) || empty($x_token))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        //gettting decrypted email from x_token
        $email = Crypter::decrypt($x_token);
        $id = Session::get('id');
        Session::flush(); //flushing all of the session data
        try {
            $user = $this->userRepo->setNewPassword($email, $id, $newPassword);
        } catch (InvalidArgumentException $ie) {
            Log::exception($ie);
            return Response::json(array('status' => false, 'message', __('responsemessages.error_occured_password_reset')), HTTPConstants::SUCCESS_CODE);
        }
        if (empty($user))
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);

        $password_reset_message = __('smstemplate.password_reset_successfully_message');
        $senderId = Config::get('sms.senderid');
        $this->smsRepo->createAppSms($user->mobile, $password_reset_message, $senderId, $user->id);

        //setting success message for password change on session to show to user
        Session::flash('password_change_success', __('responsemessages.password_changed_successfully'));
        Event::fire(ListenerConstants::APP_USER_PASSWORD_RESET, array($user));
        return Response::json(array('status' => true), HTTPConstants::SUCCESS_CODE);
    }

    public function action_resend_sms()
    {
        if (Auth::user()->mobileVerificationCode == NULL)
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $welcomeMessage = __('smstemplate.welcome_message', array('code' => Auth::user()->mobileVerificationCode));
        $this->smsRepo->createAppSms(Auth::user()->mobile, $welcomeMessage, Config::get('sms.senderId'), Auth::user()->id);
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
        return View::make('user/accountupdatepassword');
    }

    public function action_post_update_password()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $oldPassword = isset($data->oldPassword) ? $data->oldPassword : "";
        $newPassword = isset($data->newPassword) ? $data->newPassword : "";

        if (empty($oldPassword) || empty($newPassword))
            return Response::json(array('status' => false, 'message' => Lang::line('responsemessages.password_update_error')->get()), HTTPConstants::SUCCESS_CODE);

        $userId = Auth::user()->id;
        $password = Auth::user()->password;
        try {
            $user = $this->userRepo->change_password($userId, $oldPassword, $newPassword, $password);
        } catch (InvalidArgumentException $ie) {
            Log::exception($ie);
            return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
        }
        if (!empty($user)) {
            Event::first(ListenerConstants::APP_USER_PASSWORD_UPDATE, array($user));
            $passwordUpdateMessage = __('smstemplate.password_update_message');
            $this->smsRepo->createAppSms($user->mobile, $passwordUpdateMessage, Config::get('sms.senderid'), $user->id);
            return Response::json(array('status' => true, 'message' => Lang::line('responsemessages.password_update_success')->get()), HTTPConstants::SUCCESS_CODE);
        }
        return Response::json(array('status' => false, 'message' => Lang::line('responsemessages.password_update_error')->get()), HTTPConstants::SUCCESS_CODE);
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
            $this->smsRepo->createAppSms($user->mobile, $mobilePhoneUpdated, Config::get('sms.senderid'), $user->id);
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

        $isValidEmail = $this->userRepo->validateEmail($email);
        if (!$isValidEmail)
            return Response::json(array('status' => false), HTTPConstants::SUCCESS_CODE);

        $userId = Auth::user()->id;
        $user = $this->userRepo->updateEmail($userId, $email);
        if (!empty($user)) {
            Event::fire(ListenerConstants::APP_USER_EMAIL_UPDATE, array($user));
            return Response::json(array('status' => true), HTTPConstants::SUCCESS_CODE);
        }
        return Response::make(__('responseerror.database'), HTTPConstants::DATABASE_ERROR_CODE);
    }

    public function action_profile()
    {
        return View::make('user/accountinfo');
    }

    public function action_get_user_profile()
    {
        $schoolId = Auth::user()->schoolId;
        $school = School::find($schoolId);
        return Response::eloquent($school);
    }

    public function action_post_update_user()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $userId = Auth::user()->id;
        $userProfileInfo = isset($data->userProfileInfo) ? $data->userProfileInfo : NULL;
        if ($userProfileInfo == NULL)
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $updateData = array();
        if (isset($userProfileInfo->mobile) && $userProfileInfo->mobile != '') {
            $updateData['mobile'] = $userProfileInfo->mobile;
            $updateData['mobileVerificationCode'] = mt_rand(100000, 999999);
            $updateData['isVerified'] = 0;
        }
        if (isset($userProfileInfo->email) && $userProfileInfo->email != '')
            $updateData['email'] = $userProfileInfo->email;
        if (empty($updateData))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        $status = $this->userRepo->updateUserProfile($updateData, $userId);
        if (!$status)
            return Response::json(array('status' => false), HTTPConstants::SUCCESS_CODE);
        return Response::json(array('status' => true), HTTPConstants::SUCCESS_CODE);
    }

    public function action_transaction_history()
    {
        return View::make('user/transaction/transactionhistory');
    }
}
