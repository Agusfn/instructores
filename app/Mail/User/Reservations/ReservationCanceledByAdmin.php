<?php

namespace App\Mail\User\Reservations;

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
    public $user;


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
    public function __construct($user, $reservation, $reason)
    {
        $this->user = $user;
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
        return $this->view('emails.user.reservations.reservation-canceled-admin');
    }
}
