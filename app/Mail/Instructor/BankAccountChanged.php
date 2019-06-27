<?php

namespace App\Mail\Instructor;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BankAccountChanged extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * @var App\Instructor
     */
    public $instructor;

    /**
     * [$bankAccount description]
     * @var [type]
     */
    public $bankAccount;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($instructor)
    {
        $this->instructor = $instructor;
        $this->bankAccount = $instructor->bankAccount;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject("Se modificó la cuenta bancaria para extracciones de saldo");

        return $this->view('emails.instructor.bank-account-changed');
    }
}
