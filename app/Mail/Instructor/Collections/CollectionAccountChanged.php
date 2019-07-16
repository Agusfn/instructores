<?php

namespace App\Mail\Instructor\Collections;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CollectionAccountChanged extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * @var App\Instructor
     */
    public $instructor;


    /**
     * 'bank' or 'mercadopago'
     * @var string
     */
    public $accountType;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($instructor, $accountType)
    {
        $this->instructor = $instructor;
        $this->accountType = $accountType;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject("Se modificó la cuenta ".($this->accountType == "bank" ? "bancaria" : "de MercadoPago")." para extracciones de saldo");        

        return $this->view('emails.instructor.collections.collection-account-changed');
    }
}
