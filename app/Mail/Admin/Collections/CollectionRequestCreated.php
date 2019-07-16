<?php

namespace App\Mail\Admin\Collections;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CollectionRequestCreated extends Mailable
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

        if($this->collection->isToBank())
            $accType = "cta. bancaria";
        else
            $accType = "cta. de MercadoPago";

        $this->subject("Se ha solicitado una extracción a ".$accType." de ".round($this->collection->amount, 2)." ARS del instructor ".$this->instructor->name." ".$this->instructor->surname);
        return $this->view('emails.admin.collections.collection-request-created');
    }
}
