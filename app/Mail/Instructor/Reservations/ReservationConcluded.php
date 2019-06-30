<?php

namespace App\Mail\Instructor\Reservations;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationConcluded extends Mailable
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
        $this->subject("Acreditamos ".round($this->reservation->instructor_pay, 2)." ARS en tu saldo por la clase de la reserva ".$this->reservation->code);
        return $this->view('emails.instructor.reservations.reservation-concluded');
    }
}
