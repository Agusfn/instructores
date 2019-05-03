<?php

namespace App\Mail;

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
    private $instructor;


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
        return $this->view('view.name')->with("instructor", $this->instructor);
    }
}
