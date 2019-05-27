<?php

namespace App;

use Carbon\Carbon;
use App\Lib\Reservations;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    	
    protected $guarded = [];


    const STATUS_PAYMENT_PENDING = "payment-pending"; // waiting for payment.
    const STATUS_UNPAID = "unpaid"; // cancelled for lack of payment
    const STATUS_PENDING_CONFIRMATION = "pending-confirmation"; // paid, but needs to be confirmed by instructor
    const STATUS_REJECTED = "rejected"; // paid but later rejected by the instructor
    const STATUS_CONFIRMED = "confirmed"; // paid and confirmed by the instructor




    public static function findByCode($code)
    {
        return self::where("code", $code)->first();
    }


    /**
     * Generate a unique reservation code.
     * @return string
     */
    public static function generateCode()
    {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; 
        srand((double)microtime()*1000000); 
        $i = 0; 
        $pass = '' ; 

        while ($i <= 10) { 
            $num = rand() % 33; 
            $tmp = substr($chars, $num, 1); 
            $pass = $pass . $tmp; 
            $i++; 
        } 

        return $pass; 
    }




    /**
     * Returns the user that made the reservation.
     * @return App\User
     */
    public function user()
    {
        return $this->belongsTo("App\User", "user_id");
    }


    /**
     * Obtain the instructor service on which this reservation has been made.
     * @return App\InstructorService
     */
    public function service()
    {
    	return $this->belongsTo("App\InstructorService", "instructor_service_id");
    }




    /**
     * Scope a query to only include reservations within this year's activity period.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithinCurrentSeason($query)
    {
    	$startDate = date("Y")."-".Reservations::getActivityStartDate();
    	$endDate = date("Y")."-".Reservations::getActivityEndDate();

    	return $query->whereBetween("reserved_date", [$startDate, $endDate]);
    }


    /**
     * Scope a query to include only 'active' reservations. These are reservations that "occupy" space in the calendar, AKA they are booked.
     * These reservations are the ones that have been paid and have not been cancelled/rejected.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereIn("status", [
            self::STATUS_PENDING_CONFIRMATION,
            self::STATUS_CONFIRMED
        ]);
    }




    public function date()
    {
    	return Carbon::parse($this->reserved_date);
    }


    /**
     * Get the reserved time blocks that span this reservation.
     * @return int[]
     */
    public function reservedTimeBlocks()
    {
    	return Reservations::hourRangeToBlocks($this->reserved_time_start, $this->reserved_time_end);
    }

}
