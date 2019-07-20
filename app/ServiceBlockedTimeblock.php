<?php

namespace App;

use App\Lib\Reservations;
use Illuminate\Database\Eloquent\Model;

class ServiceBlockedTimeblock extends Model
{
    
	protected $guarded = [];

	public $timestamps = false;



	/**
	 * The attributes that are mutated to carbon date object.
	 * @var array
	 */
	public $dates = ["date"];


	/**
	 * Get the related instructor service.
	 * @return App\InstructorService|null
	 */
	public function instructorService()
	{
		return $this->belongsTo("App\InstructorService");
	}


    /**
     * Scope a query to only include reservations within this year's activity period, ignoring days previous than today, if we are within the season.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLeftWithinCurrentSeason($query)
    {
    	$startDate = Reservations::nearestActivityDayCurrentYear() ?? Carbon::today();
    	$endDate = Reservations::getCurrentYearActivityEnd();

    	return $query->whereBetween("date", [$startDate->format("Y-m-d"), $endDate->format("Y-m-d")]);
    }


    /**
     * Scope a query to order the timeblocks from the beginning to end.
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeOrderAsc($query)
    {
    	return $query->orderBy("date", "ASC")->orderBy("time_block", "ASC");
    }


	/**
	 * Get the readable hour range of this unavailable time block.
	 * @param  [type] $compact [description]
	 * @return [type]          [description]
	 */
	public function readableHourRange($compact = false)
	{		 
		return Reservations::blocksToReadableHourRange($this->time_block, $this->time_block, $compact);
	}





}
