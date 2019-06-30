<?php

namespace App\Mail\Admin\Payments;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentChargebacked extends Mailable
{
    use Queueable, SerializesModels;


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
    public function __construct($payment, $reservation)
    {
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
        $this->subject("Se ha recibido un contracargo del pago de MercadoPago de la reserva ".$this->reservation->code." por ".round($this->payment->total_amount, 2)." ARS");
        return $this->view('emails.admin.payments.payment-chargebacked');
    }

}