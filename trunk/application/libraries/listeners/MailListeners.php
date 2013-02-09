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



    }
}

