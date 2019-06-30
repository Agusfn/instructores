<?php

namespace App\Mail\Instructor\Reservations;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationCanceledByAdmin extends Mailable
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
     * @var string
     */
    public $reason;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($instructor, $reservation, $reason)
    {
        $this->instructor = $instructor;
        $this->reservation = $reservation;
        $this->reason = $reason;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject("La reserva ".$this->reservation->code." de clases de ".$this->reservation->sport_discipline." ha sido cancelada.");
        return $this->view('emails.instructor.reservations.reservation-canceled-admin');
    }
}
