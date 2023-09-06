<?php

namespace Kapi\Listeners\User;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Kapi\Events\User\Registered;

class SendEmailVerification implements ShouldQueue
{
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'emails';

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        Mail::raw('This is a test email after user register', function ($message) use ($event) {
            $message->to($event->user->email, 'Kapi Test')
                ->from(env('MAIL_FROM', 'kurianvarkey@yahoo.com'), 'Kapi API from')
                ->subject('Kapi API Second email');
        });
    }
}
