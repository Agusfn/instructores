<?php

namespace App\Console\Commands;

use App\Reservation;
use Illuminate\Console\Command;

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

            if($reservation->lastPayment->isFailed()) {

                $reservation->status = Reservation::STATUS_PAYMENT_FAILED;
                $reservation->save();  
            }

        }
    }


}
