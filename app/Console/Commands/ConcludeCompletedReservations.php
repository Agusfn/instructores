<?php

namespace App\Console\Commands;

use App\Reservation;
use Illuminate\Console\Command;

class ConcludeCompletedReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:conclude-completed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Conclude reservations that have had their classes dictated, and pay the instructor to their wallet.';

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
        
        $reservations = Reservation::getReservationsToConclude();

        foreach ($reservations as $reservation) {
            $reservation->conclude();
        }

    }
}
