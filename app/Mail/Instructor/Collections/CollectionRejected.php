<?php

namespace App\Mail\Instructor\Collections;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CollectionRejected extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var App\Instructor
     */
    public $instructor;


    /**
     * @var App\InstructorCollection
     */
    public $collection;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($instructor, $collection)
    {
        $this->instructor = $instructor;
        $this->collection = $collection;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject("Se ha rechazado tu solicitud de retiro de saldo de ".$this->collection->amount." ARS a tu cuenta bancaria");
        
        return $this->view('emails.instructor.collections.collection-rejected');
    }
}
