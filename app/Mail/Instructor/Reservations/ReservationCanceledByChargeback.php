<?php

namespace App\Mail\Instructor\Reservations;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationCanceledByChargeback extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * @var App\Instructor
     */
    public $instructor;


    /**
     * @var App\Reservation
     */
    public $reservation;



    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($instructor, $reservation)
    {
        $this->instructor = $instructor;
        $this->reservation = $reservation;
    }



    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject("Se canceló la reserva ".$this->reservation->code." por un problema en el pago");
        return $this->view('emails.instructor.reservations.reservation-canceled-chargeback');
    }
}
