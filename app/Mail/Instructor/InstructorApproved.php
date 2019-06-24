<?php

namespace App\Mail\Instructor;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InstructorApproved extends Mailable
{
    use Queueable, SerializesModels;



    /**
     * @var App\Instructor
     */
    public $instructor;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($instructor)
    {
        $this->instructor = $instructor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject("Tu cuenta ha sido aprobada");

        return $this->view('emails.instructor.instructor-approved');
    }
}
