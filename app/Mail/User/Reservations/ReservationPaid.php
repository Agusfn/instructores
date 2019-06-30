<?php

namespace App\Mail\User\Reservations;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationPaid extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * @var App\Instructor
     */
    public $user;


    /**
     * @var App\Reservation
     */
    public $reservation;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $reservation)
    {
        $this->user = $user;
        $this->reservation = $reservation;
    }



    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject("Se acreditó tu pago por la reserva ".$this->reservation->code." de clases de ".ucfirst($this->reservation->sport_discipline));
        return $this->view('emails.user.reservations.reservation-paid');
    }
}
