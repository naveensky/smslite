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
        Message::send(function ($message) use ($user) {
            $message->to($user['email']);
            $message->from(Config::get('email.from_email'), Config::get('email.adminName'));
            //email subject can be changed from language file
            $message->subject(__('emailsubjects.welcome_email_subject'));
            $message->body("view: auth.email.welcome");
           // You can add View data by simply setting the value
            // to the message.
            $message->body->result = $user;
            $message->html(true);
        });
    }


    public function sendForgottenPasswordEmail($user)
    {
        $user = $user->to_array();
        Message::send(function ($message) use ($user) {
            $message->to($user['email']);
            $message->from(Config::get('email.from_email'), Config::get('email.adminName'));
            $message->subject(__('emailsubjects.forgot_password_email_subject'));
            $message->body("view: auth.email.forgotPassword");
            // You can add View data by simply setting the value
            // to the message.
            $message->body->result = $user;
            $message->html(true);
        });
    }


    public function sendForgottenPasswordCompleteEmail($user)
    {
        $user = $user->to_array();
        Message::send(function ($message) use ($user) {
            $message->to($user['email']);
            $message->from(Config::get('email.from_email'), Config::get('email.adminName'));
            $message->subject(__('emailsubjects.new_password_email_subject'));
            $message->body("view: auth.email.newpassword");
            // You can add View data by simply setting the value
            // to the message.
            $message->body->result = $user;
            $message->html(true);
        });
    }

    public function sendDeactivateAccountEmail($user)
    {
        $user = $user->to_array();
        Message::send(function ($message) use ($user) {
            $message->to($user['email']);
            $message->from(Config::get('email.from_email'), Config::get('email.adminName'));
            $message->subject(__('emailsubjects.deactivate_account_email_subject'));
            $message->body("view: auth.email.accountDeactivate");
            // You can add View data by simply setting the value
            // to the message.
            $message->body->result = $user;
            $message->html(true);
        });
    }

    public function sendDeleteAccountEmail($user)
    {
        $user = $user->to_array();
        Message::send(function ($message) use ($user) {
            $message->to($user['email']);
            $message->from(Config::get('email.from_email'), Config::get('email.adminName'));
            $message->subject(__('emailsubjects.delete_account_email_subject'));
            $message->body("view: auth.email.accountDeleted");
            $message->body->result = $user;
            $message->html(true);
        });
    }

    public function sendAccountRestore($user)
    {
        $user = $user->to_array();
        Message::send(function ($message) use ($user) {
            $message->to($user['email']);
            $message->from(Config::get('email.from_email'), Config::get('email.adminName'));
            $message->subject(__('emailsubjects.restore_account_email_subject'));
            $message->body("view: auth.email.deleted");
            $message->body->result = $user;
            $message->html(true);
        });
    }
}
