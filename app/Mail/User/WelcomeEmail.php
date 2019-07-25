<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * @var App\User
     */
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->user->hasSocialLogin()) {

            $this->subject("Bienvenido!");
            return $this->view('emails.user.welcome');
        }
        else {
            $this->subject("Bienvenido! Verifica tu e-mail para continuar.");
            return $this->view('emails.user.welcome')->with("verification_url", $this->verificationUrl());
        }
    }


    /**
     * Get the verification URL for the user. 
     * In the URL obtained, "email" will go as the route parameter (the name of the key is alphabetically first) 
     * The rest will be as GET params.
     *
     * @return string
     */
    protected function verificationUrl()
    {
        return URL::temporarySignedRoute(
            'user.verify-email',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            ['email' => $this->user->email]
        );
    }





    
}
