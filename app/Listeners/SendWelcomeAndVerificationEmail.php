<?php

namespace App\Listeners;

use App\User;
use App\Instructor;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class SendWelcomeAndVerificationEmail
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if (!$event->user->hasVerifiedEmail()) {
            $event->user->sendWelcomeAndVerificationEmail();
        } 
    }
}
