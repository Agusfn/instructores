<?php

namespace App\Console\Commands;

use Log;
use App\Reservation;
use Illuminate\Console\Command;
use App\Mail\User\Reservations\ReservationUnpaidExpired;
use Illuminate\Support\Facades\Mail;


class CancelUnpaidReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:cancel-unpaid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Cancel reservations that haven't been paid during their payment retry time.";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $reservations = Reservation::paymentTimeExpired()->get();

        foreach($reservations as $reservation) {

            if($reservation->lastPayment->isFailed() || $reservation->lastPayment->isPending()) {

                
                // cancel unpaid ticket/atm payment
                if($reservation->lastPayment->isPending() && $reservation->lastPayment->isMercadoPago()) {

                    if(!$reservation->lastPayment->cancel()) {
                        Log::warning("CRON: Error cancelando pago de reserva ".$reservation->code." expirada por falta de pago del ticket/cajero.");
                        continue;
                    }

                }

                $reservation->cancelForPaymentFailed();

                Mail::to($reservation->user)->send(new ReservationUnpaidExpired($reservation->user, $reservation));
            }

        }
    }


}
