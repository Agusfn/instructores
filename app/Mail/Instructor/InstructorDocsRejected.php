<?php

namespace App\Mail\Instructor;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InstructorDocsRejected extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * @var App\Instructor
     */
    public $instructor;

    /**
     * @var string
     */
    public $reason;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($instructor, $reason)
    {
        $this->instructor = $instructor;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject("No se aprobó la documentación de instructor enviada");
        
        return $this->view('emails.instructor.instructor-docs-rejected');
    }
}
