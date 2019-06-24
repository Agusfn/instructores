<?php

namespace App\Mail\User\Payments;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessingPaymentFailed extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * @var App\Instructor
     */
    public $user;


    /**
     * @var App\ReservationPayment
     */
    public $payment;


    /**
     * @var App\Reservation
     */
    public $reservation;



    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $payment, $reservation)
    {
        $this->user = $user;
        $this->payment = $payment;
        $this->reservation = $reservation;
    }



    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject("No se pudo concretar tu pago por la reserva ".$this->reservation->code);
        return $this->view('emails.user.payments.payment-failed');
    }
}
