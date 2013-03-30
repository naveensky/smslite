<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 2/7/13
 * Time: 3:56 PM
 * To change this template use File | Settings | File Templates.
 */
class MailServices
{
    public function sendActivationEmail($user)
    {
        $user = $user->to_array();
        try {
            Message::send(function ($message) use ($user) {
                $message->to($user['email']);
                $message->from(Config::get('email.from_email'), Config::get('email.adminName'));
                //email subject can be changed from language file
                $message->subject(__('emailsubjects.welcome_email_subject'));
                $message->body("view: user.email.welcome");
                // You can add View data by simply setting the value
                // to the message.
                $message->body->result = $user;
                $message->html(true);
            });
        } catch (Exception $e) {
            Log::exception($e);
        }
    }


    public function sendForgottenPasswordEmail($user)
    {
        $user = $user->to_array();
        try {
            Message::send(function ($message) use ($user) {
                $message->to($user['email']);
                $message->from(Config::get('email.from_email'), Config::get('email.adminName'));
                $message->subject(__('emailsubjects.forgot_password_email_subject'));
                $message->body("view: user.email.forgotpassword");
                // You can add View data by simply setting the value
                // to the message.
                $message->body->result = $user;
                $message->html(true);
            });
        } catch (Exception $e) {
            Log::exception($e);
        }
    }


    public function sendForgottenPasswordCompleteEmail($user)
    {
        $user = $user->to_array();
        try {
            Message::send(function ($message) use ($user) {
                $message->to($user['email']);
                $message->from(Config::get('email.from_email'), Config::get('email.adminName'));
                $message->subject(__('emailsubjects.new_password_email_subject'));
                $message->body("view: user.email.passwordreset");
                // You can add View data by simply setting the value
                // to the message.
                $message->body->result = $user;
                $message->html(true);
            });
        } catch (Exception $e) {
            Log::exception($e);
        }
    }

    public function sendEmailOnCreditsAllocation($emailData)
    {
        try {
            Message::send(function ($message) use ($emailData) {
                $message->to($emailData['schoolEmail']);
                $message->from(Config::get('email.from_email'), Config::get('email.adminName'));
                $message->subject(__('emailsubjects.credits_allocated'));
                $message->body("view: user.email.creditsallocation");
                // You can add View data by simply setting the value
                // to the message.
                $message->body->result = $emailData;
                $message->html(true);
            });
        } catch (Exception $e) {
            Log::exception($e);
        }
    }

    public function sendEmailToSystemAdminOnCredit($emailData)
    {
        $emails = Config::get('app.system_alert_emails');
        try {
            Message::send(function ($message) use ($emailData, $emails) {
                $message->to($emails);
                $message->from(Config::get('email.from_email'), Config::get('email.adminName'));
                $message->subject(__('emailsubjects.credits_allocated_admin'));
                $message->body("view: user.email.admin.creditsallocation");
                // You can add View data by simply setting the value
                // to the message.
                $message->body->result = $emailData;
                $message->html(true);
            });
        } catch (Exception $e) {
            Log::exception($e);
        }
    }


    public function sendDeactivateAccountEmail($user)
    {
        $user = $user->to_array();
        try {
            Message::send(function ($message) use ($user) {
                $message->to($user['email']);
                $message->from(Config::get('email.from_email'), Config::get('email.adminName'));
                $message->subject(__('emailsubjects.deactivate_account_email_subject'));
                $message->body("view: user.email.accountdeactivate");
                // You can add View data by simply setting the value
                // to the message.
                $message->body->result = $user;
                $message->html(true);
            });
        } catch (Exception $e) {
            Log::exception($e);
        }
    }

    public function sendDeleteAccountEmail($user)
    {
        $user = $user->to_array();
        try {
            Message::send(function ($message) use ($user) {
                $message->to($user['email']);
                $message->from(Config::get('email.from_email'), Config::get('email.adminName'));
                $message->subject(__('emailsubjects.delete_account_email_subject'));
                $message->body("view: user.email.accountdeleted");
                $message->body->result = $user;
                $message->html(true);
            });
        } catch (Exception $e) {
            Log::exception($e);
        }
    }

    public function sendAccountRestore($user)
    {
        $user = $user->to_array();
        try {
            Message::send(function ($message) use ($user) {
                $message->to($user['email']);
                $message->from(Config::get('email.from_email'), Config::get('email.adminName'));
                $message->subject(__('emailsubjects.restore_account_email_subject'));
                $message->body("view: user.email.deleted");
                $message->body->result = $user;
                $message->html(true);
            });
        } catch (Exception $e) {
            Log::exception($e);
        }
    }

    public function sendUserEmailUpdate($user)
    {
        $user = $user->to_array();
        try {
            Message::send(function ($message) use ($user) {
                $message->to($user['email']);
                $message->from(Config::get('email.from_email'), Config::get('email.adminName'));
                $message->subject(__('emailsubjects.account_email_update_subject'));
                $message->body("view: user.email.useremailupdate");
                $message->body->result = $user;
                $message->html(true);
            });
        } catch (Exception $e) {
            Log::exception($e);
        }
    }
}
