<?php

namespace App;

use Carbon\Carbon;
use App\Lib\Reservations;
use App\Lib\Helpers\Dates;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;


class Reservation extends Model
{
   
    /**
     * Length in characters of reservation code.
     */
    const CODE_LENGTH = 7;



    /**
     * Time in hours after a reservation being created in which it gets cancelled if it's not paid.
     */
    const RETRY_PAYMENT_TIME_HS = 48;

    /**
     * Time in hours given to an instructor to confirm a paid reservation. After this time, the reservation gets rejected automatically.
     */
    const AUTO_REJECT_TIME_HS = 24;


    /**
     * Time in hours that must pass after the classes have been dictated in a confirmed reservation, in order to conclude it AND pay the instructor.
     * Concluded reservations can't be refunded or have claims.
     */
    const CONCLUDE_TIME_HS = 24;




    const STATUS_PAYMENT_PENDING = "payment-pending"; // Waiting for payment. Either it's processing or it failed, and another one must be done within x time.
    const STATUS_PENDING_CONFIRMATION = "pending-confirmation"; // Paid. Pending instructor confirmation.
    const STATUS_PAYMENT_FAILED = "payment-failed"; // Payment not done within time, so reservation failed as well.
    const STATUS_REJECTED = "rejected"; // Paid but later rejected by the instructor.
    const STATUS_CONFIRMED = "confirmed"; // Paid and confirmed by the instructor.
    const STATUS_CONCLUDED = "concluded"; // 24hs after classes. Can't be canceled/refunded/claimed.
    const STATUS_CANCELED = "canceled"; // canceled (with refund), or chargebacked
    


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
        return $this->hasOne('App\ReservationPayment')->latest();
    }


    /**
     * Obtain the claim associated with this reservation (if it exists)
     * @return App\Claim|null
     */
    public function claim()
    {
        return $this->hasOne("App\Claim");
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
     * Scope a query to only include reservations within this year's activity period, ignoring days previous than today, if we are within the season.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLeftWithinCurrentSeason($query)
    {
    	$startDate = date("Y")."-".Reservations::getActivityStartDate();
        $today = date("Y-m-d");

        $startDate = ($startDate < $today) ? $today : $startDate;
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
            self::STATUS_PAYMENT_PENDING,
            self::STATUS_PENDING_CONFIRMATION,
            self::STATUS_CONFIRMED
        ]);
    }


    /**
     * Scope a query to retrieve reservations which have not been paid and: passed their payment retry period or have passed their classes start time.
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopePaymentTimeExpired($query)
    {
        return $query->where("status", self::STATUS_PAYMENT_PENDING)
            ->where(function ($query) { 

                $query->where('created_at', '<=', date("Y-m-d H:i:s", strtotime("-".self::RETRY_PAYMENT_TIME_HS." hour")))
                    ->orWhereRaw("STR_TO_DATE(concat(reserved_class_date,' ',reserved_time_start,':00:00'),'%Y-%m-%d %H:%i:%s') <= NOW()");

            });
    }

    /**
     * Scope a query to retrieve paid reservations that are pending instructor confirmation and: passed the confirmation period or have passed their class start time.
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeConfirmationTimeExpired($query)
    {
        return $query->where("status", self::STATUS_PENDING_CONFIRMATION)
            ->where(function($query) {

                $query->where('updated_at', '<=', date("Y-m-d H:i:s", strtotime("-".self::AUTO_REJECT_TIME_HS." hour")))
                    ->orWhereRaw("STR_TO_DATE(concat(reserved_class_date,' ',reserved_time_start,':00:00'),'%Y-%m-%d %H:%i:%s') <= NOW()");

            });
    }



    /**
     * Get collection of confirmed reservations in which a certain amount of hours passed since the classes have finished, and are considered concluded.
     * These reservations must have their status change to concluded.
     * @return [type] [description]
     */
    public static function getReservationsToConclude()
    {
        $reservations = DB::table('reservations')
            ->where("status", self::STATUS_CONFIRMED)
            ->whereRaw(
                "STR_TO_DATE(concat(reserved_class_date,' ',reserved_time_end,':00:00'),'%Y-%m-%d %H:%i:%s') < ?",
                date("Y-m-d H:i:s", strtotime("-".self::CONCLUDE_TIME_HS." hour"))
            )
            ->get();

        return self::hydrate($reservations->toArray());
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

    public function isConcluded()
    {
        return $this->status == self::STATUS_CONCLUDED;
    }

    public function isCanceled() 
    {
        return $this->status == self::STATUS_CANCELED;
    }



    /**
     * Get an array of the numbers of the reserved time blocks that span this reservation.
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
    /*public function priceBreakdown()
    {
        return json_decode($this->json_breakdown, true);
    }*/


    /**
     * Get the total amount of people included in this reservation (adults+kids)
     * @return int
     */
    public function personAmount()
    {
        return $this->adults_amount + $this->kids_amount;
    }



    /**
     * Called when its payment has been completed. It updates the reservation data.
     * @return [type] [description]
     */
    public function updateStatusIfPaid()
    {
        $this->load("lastPayment"); // refresh in case a new payment was created
        if($this->isPaymentPending() && $this->lastPayment->isSuccessful()) {

            $this->adjustPayProcessorFee($this->lastPayment->payment_provider_fee);

            $this->fill([
                "status" => Reservation::STATUS_PENDING_CONFIRMATION,
                "final_price" => $this->lastPayment->total_amount,
                "mp_financing_cost" => $this->lastPayment->financing_costs,
                "mp_installment_amt" => $this->lastPayment->mercadopagoPayment->installment_amount
            ]);

            $this->save();

            Mail::to($this->instructor)->send(new \App\Mail\Instructor\Reservations\ReservationPaid($this->instructor, $this));
            Mail::to($this->user)->send(new \App\Mail\User\Reservations\ReservationPaid($this->user, $this));
        }
    }



    /**
     * Cancel the reservation for lack of payment.
     */
    public function cancelForPaymentFailed()
    {
        if(!$this->isPaymentPending())
            return false;

        $this->status = self::STATUS_PAYMENT_FAILED;
        $this->save();

        $this->releaseTimeFromInstructor();

    }



    /**
     * Reject a reservation and save it.
     * @param  string $reason
     * @return null
     */
    public function reject($reason)
    {   
        if(!$this->isPendingConfirmation())
            return;

        $this->fill([
            "status" => self::STATUS_REJECTED,
            "reject_message" => $reason
        ]);
        
        $this->save();

        $this->releaseTimeFromInstructor();
    }



    /**
     * Cancel this reservation, if it's payment pending, pending confirmation, or confirmed only.
     * Also, release the time from InstructorService booking indexes.
     * @return [type] [description]
     */
    public function cancel()
    {
        if(!$this->isPaymentPending() && !$this->isPendingConfirmation() && !$this->isConfirmed())
            return false;

        $this->status = self::STATUS_CANCELED;
        $this->save();

        $this->releaseTimeFromInstructor();
    }




    /**
     * Release the reservation occupied booked time from the instructor service.
     * @return null
     */
    private function releaseTimeFromInstructor()
    {
        $this->service->unoccupyBlocksInIndexes(
            $this->reserved_class_date->month, 
            $this->reserved_class_date->day,
            Reservations::hourRangeToBlocks($this->reserved_time_start, $this->reserved_time_end)
        );
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
                \Log::notice("Fee difference of ".$feeDifference." ARS for reservation ".$this->code);
        }
    }


    /**
     * Conclude a reservation and pay the instructor the ammount to their wallet.
     * @return null
     */
    public function conclude()
    {

        if($this->status != self::STATUS_CONFIRMED)
            return;

        $movement = $this->instructor->wallet->addInstructorPayment($this->id, $this->instructor_pay);

        $this->status = self::STATUS_CONCLUDED;
        $this->instructor_wallet_movement_id = $movement->id;
        $this->save();
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


}


