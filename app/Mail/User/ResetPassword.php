<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * @var App\User
     */
    public $user;

    /**
     * @var string
     */
    public $resetToken;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $resetToken)
    {
        $this->user = $user;
        $this->resetToken = $resetToken;
    }



    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject("Recupera la contraseña de tu cuenta");
        return $this->view('emails.user.reset-password');
    }
}
