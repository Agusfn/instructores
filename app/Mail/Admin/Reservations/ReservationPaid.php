<?php

namespace App\Mail\Admin\Reservations;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationPaid extends Mailable
{
    use Queueable, SerializesModels;


    public $reservation;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject("Un usuario ha pagado una reserva");
        return $this->view('emails.admin.reservations.reservation-paid');
    }
}
