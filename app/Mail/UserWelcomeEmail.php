<?php

namespace App\Mail;

use App\User;
use App\Instructor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserWelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * User
     * @var App\User
     */
    private $user;


    /**
     * Type of the user who is registering, to send the email.
     * @var string
     */
    private $user_type;


    /**
     * Create a new message instance.
     *
     * @param Illuminate\Foundation\Auth\User $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;

        if($user instanceof User)
            $this->user_type = "user";
        else
            $this->user_type = "instructor";
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.'.$this->user_type.'.registered')->with([
            "user" => $this->user, 
            "verification_url" => $this->verificationUrl()
        ]);
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
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            ['type' => $this->user_type, 'email' => $this->user->email]
        );
    }



}
