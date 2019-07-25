<?php

namespace App\Mail\Instructor;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;



    /**
     * @var App\Instructor
     */
    public $instructor;


    /**
     * @var string
     */
    public $resetToken;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($instructor, $resetToken)
    {
        $this->instructor = $instructor;
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
        return $this->view('emails.instructor.reset-password');    
    }
}
