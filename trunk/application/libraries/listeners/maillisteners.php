<?php

//create the listener and fire all events
$listener = new MailListener();
$listener->runEvents();

class MailListener
{
    private $mailService;

    /**
     * Run all events in class
     */
    public function runEvents()
    {
        Event::listen(ListenerConstants::APP_USER_CREATED, function ($user) {
            $mailService = new MailServices();
            $mailService->sendActivationEmail($user);
        });

        Event::listen(ListenerConstants::APP_USER_DEACTIVATED, function ($user) {
            $mailService = new MailServices();
            $mailService->sendDeactivateAccountEmail($user);
        });
        Event::listen(ListenerConstants::APP_USER_RESTORE, function ($user) {
            $mailService = new MailServices();
            $mailService->sendDeactivateAccountEmail($user);
        });
        Event::listen(ListenerConstants::APP_USER_EMAIL_UPDATE, function ($user) {
            $mailService = new MailServices();
            $mailService->sendUserEmailUpdate($user);
        });
        Event::listen(ListenerConstants::APP_USER_PASSWORD_FORGOT, function ($user) {
            $mailService = new MailServices();
            $mailService->sendForgottenPasswordEmail($user);
        });
        Event::listen(ListenerConstants::APP_USER_DELETE, function ($user) {
            $mailService = new MailServices();
            $mailService->sendDeleteAccountEmail($user);
        });
        Event::listen(ListenerConstants::APP_USER_PASSWORD_RESET, function ($user) {
            $mailService = new MailServices();
            $mailService->sendForgottenPasswordCompleteEmail($user);
        });
        Event::listen(ListenerConstants::APP_ADMIN_CREDITS_ALLOTED, function ($allocateData) {
            $mailService = new MailServices();
            $mailService->sendEmailToSystemAdminOnCredit($allocateData);
        });
        Event::listen(ListenerConstants::APP_CREDITS_ALLOTED, function ($allocateData) {
            $mailService = new MailServices();
            $mailService->sendEmailOnCreditsAllocation($allocateData);
        });

    }
}

