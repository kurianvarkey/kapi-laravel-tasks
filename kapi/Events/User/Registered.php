<?php

namespace Kapi\Events\User;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;
use Kapi\Listeners\User\LogUserRegistered;
use Kapi\Listeners\User\SendEmailVerification;
use Kapi\Models\User;

class Registered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public User $user)
    {
        Event::listen(
            Registered::class,
            [SendEmailVerification::class, 'handle']
        );

        Event::listen(
            Registered::class,
            [LogUserRegistered::class, 'handle']
        );
    }
}
