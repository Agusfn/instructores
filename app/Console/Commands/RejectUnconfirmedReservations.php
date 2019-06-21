<?php

namespace App\Console\Commands;

use Log;
use App\Reservation;
use Illuminate\Console\Command;

class RejectUnconfirmedReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:reject-unconfirmed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Reject reservations pending confirmation which haven't been confirmed by the instructor.";

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
        $reservations = Reservation::confirmationTimeExpired()->get();

        foreach($reservations as $reservation) {

            if(!$reservation->lastPayment->refund()) {
                Log::warning("CRON: Error reembolsando pago de reserva ".$reservation->code." rechazada automáticamente por no confirmación del instructor.");
                continue;
            }

            $reservation->reject("Reserva rechazada de forma automática por falta de confirmación del instructor.");
                
            //<mail>

        }
    }
}
