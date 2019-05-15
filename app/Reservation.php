<?php

namespace App;

use Carbon\Carbon;
use App\Lib\Reservations;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    	
    protected $guarded = [];



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
