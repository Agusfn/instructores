<?php

namespace App;

use Carbon\Carbon;
use App\Lib\Reservations;
use App\Lib\Helpers\Dates;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
   
    /**
     * Length of reservation code.
     */
    const CODE_LENGTH = 7;

    const STATUS_PAYMENT_PENDING = "payment-pending"; // Waiting for payment. Either it's processing or it failed, and another one must be done within x time.
    const STATUS_PENDING_CONFIRMATION = "pending-confirmation"; // Paid. Pending instructor confirmation.
    const STATUS_PAYMENT_FAILED = "payment-failed"; // Payment not done within time, so reservation failed as well.
    const STATUS_REJECTED = "rejected"; // Paid but later rejected by the instructor.
    const STATUS_CONFIRMED = "confirmed"; // Paid and confirmed by the instructor.
    const STATUS_CANCELED = "canceled"; // conceled voluntarily (with refund), or chargebacked


    protected $guarded = [];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ["reserved_class_date"];




    /**
     * Obtains the reservation with the given code.
     * @param  string $code
     * @return App\Reservation|null
     */
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

        while ($i < self::CODE_LENGTH) { 
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
     * The instructor who provides the classes of this reservation.
     * @return App\Instructor
     */
    public function instructor()
    {
        return $this->belongsTo("App\Instructor");
    }


    /**
     * Obtain the reservation payments.
     * @return [type] [description]
     */
    public function payments()
    {
        return $this->hasMany("App\ReservationPayment");
    }


    /**
     * Obtain the last payment associated with this reservation that was made.
     * @return App\ReservationPayment|null
     */
    public function lastPayment()
    {
        return $this->payments()->orderBy("created_at", "DESC")->first();
    }



    /**
     * Scope a query to find a reservation by code (used to stack with other queries)
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string $code
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithCode($query, $code)
    {
        return $query->where("code", $code);
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

    	return $query->whereBetween("reserved_class_date", [$startDate, $endDate]);
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


    /**
     * Get the reserved time blocks that span this reservation.
     * @return int[]
     */
    public function reservedTimeBlocks()
    {
    	return Reservations::hourRangeToBlocks($this->reserved_time_start, $this->reserved_time_end);
    }


    /**
     * Gets a readable hour range string of the hours to be reserved.
     * @return string
     */
    public function readableHourRange($compact = false)
    {
        if(!$compact)
            return Dates::hoursToReadableHourRange($this->reserved_time_start, $this->reserved_time_end);

        else
            return $this->reserved_time_start."-".$this->reserved_time_end." hs";
    }


    /**
     * Returns the price breakdown table/array of the reservation (for display uses only)
     * @return array
     */
    public function priceBreakdown()
    {
        return json_decode($this->json_breakdown, true);
    }


    /**
     * Get the total amount of people included in this reservation (adults+kids)
     * @return int
     */
    public function personAmount()
    {
        return $this->adults_amount + $this->kids_amount;
    }



    public function isPaymentPending()
    {
        return $this->status == self::STATUS_PAYMENT_PENDING;
    }

    public function isPendingConfirmation()
    {
        return $this->status == self::STATUS_PENDING_CONFIRMATION;
    }

    public function isFailed()
    {
        return $this->status == self::STATUS_PAYMENT_FAILED;
    }

    public function isRejected()
    {
        return $this->status == self::STATUS_REJECTED;
    }

    public function isConfirmed()
    {
        return $this->status == self::STATUS_CONFIRMED;
    }

    public function isCanceled() 
    {
        return $this->status == self::STATUS_CANCELED;
    }




    /**
     * In the reservation process, the Reservation is created and then the payment is excecuted and created.
     * After a payment is excecuted, the processing fee might be slightly different than the one predicted and saved into the reservation.
     * This method is to adjust and reflect that difference into the reservation, to keep storing real data. As the total remains the same, 
     * the difference will directly affect our service fee.
     * save() MUST BE CALLED AFTER CALLING THIS.
     * 
     * @param  float $realPayFee
     * @return null
     */
    public function adjustPayProcessorFee($realPayFee)
    {
        $feeDifference = $this->payment_proc_fee - $realPayFee;

        if($feeDifference != 0) {
            $this->payment_proc_fee = $realPayFee;
            $this->service_fee += $feeDifference;

            if($feeDifference < 0) // against
                \Log::notice("MP fee difference of ".$feeDifference." ARS for reservation ".$this->code);
        }
    }



}


