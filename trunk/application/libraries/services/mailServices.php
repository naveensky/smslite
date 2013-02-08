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
    public function sendActivationEmail($toEmail,$activationCode)
    {

        Message::send(function ($message) use($toEmail,$activationCode) {
            $message->to($toEmail);
            $message->from(Config::get('email.from_email'),Config::get('email.adminName') );

            $message->subject('SMSLITE - Welcome Mail');

            $message->body("view: auth.email.welcome");

            // You can add View data by simply setting the value
            // to the message.
            $message->body->activation_code = $activationCode;

            $message->html(true);
        });
    }


    public function sendForgottenPasswordEmail($toEmail,$forgottenPasswordCode)
    {

        Message::send(function ($message) use($toEmail,$forgottenPasswordCode) {
            $message->to($toEmail);
            $message->from(Config::get('email.from_email'), Config::get('email.adminName'));

            $message->subject('SMSLITE - Forgot Password');

            $message->body("view: auth.email.forgotpassword");

            // You can add View data by simply setting the value
            // to the message.
            $message->body->activation_code = $forgottenPasswordCode;

            $message->html(true);
        });
    }


    public function sendForgottenPasswordCompleteEmail($toEmail,$NewPassword)
    {

        Message::send(function ($message) use($toEmail,$NewPassword) {
            $message->to($toEmail);
            $message->from(Config::get('email.from_email'), Config::get('email.adminName'));

            $message->subject('SMSLITE - New Password');

            $message->body("view: auth.email.newpassword");

            // You can add View data by simply setting the value
            // to the message.
            $message->body->activation_code = $NewPassword;

            $message->html(true);
        });
    }

    public function sendDeactivateAccountEmail($toEmail,$activationCode)
    {

        Message::send(function ($message) use($toEmail,$activationCode) {
            $message->to($toEmail);
            $message->from(Config::get('email.from_email'), Config::get('email.adminName'));

            $message->subject('SMSLITE - Account Deactivation');

            $message->body("view: auth.email.deactivate");

            // You can add View data by simply setting the value
            // to the message.
            $message->body->activation_code = $activationCode;

            $message->html(true);
        });
    }

    public function sendDeleteAccountEmail($toEmail)
    {

        Message::send(function ($message) use($toEmail) {
            $message->to($toEmail);
            $message->from(Config::get('email.from_email'), Config::get('email.adminName'));

            $message->subject('SMSLITE - Account Deleted');

            $message->body("view: auth.email.deleted");

            $message->html(true);
        });
    }
}
