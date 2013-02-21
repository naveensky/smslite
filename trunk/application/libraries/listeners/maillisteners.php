<?php

//create the listener and fire all events
$listener = new MailListener();
$listener->runEvents();

class MailListener
{
    private $mailService;

    public function __construct()
    {
        $this->mailService = new MailServices();
    }

    /**
     * Run all events in class
     */
    public function runEvents()
    {
        Event::listen(ListenerConstants::APP_USER_CREATED, function ($user) {
            $this->mailService->sendActivationEmail($user);
        });

        Event::listen(ListenerConstants::APP_USER_DEACTIVATED, function ($user) {
            $this->mailService->sendDeactivateAccountEmail($user);
        });
        Event::listen(ListenerConstants::APP_USER_RESTORE, function ($user) {
            $this->mailService->sendDeactivateAccountEmail($user);
        });
        Event::listen(ListenerConstants::APP_USER_EMAIL_UPDATE, function ($user) {
            $this->mailService->sendUserEmailUpdate($user);
        });
        Event::listen(ListenerConstants::APP_USER_PASSWORD_FORGOT, function ($user) {
            $this->mailService->sendForgottenPasswordEmail($user);
        });
        Event::listen(ListenerConstants::APP_USER_DELETE, function ($user) {
            $this->mailService->sendDeleteAccountEmail($user);
        });
        Event::listen(ListenerConstants::APP_USER_PASSWORD_RESET, function ($user) {
            $this->mailService->sendForgottenPasswordCompleteEmail($user);
        });

    }
}

