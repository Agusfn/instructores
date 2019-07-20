<?php

namespace App;


use App\Lib\Reservations;
use App\Lib\Helpers\Dates;
use Illuminate\Database\Eloquent\Model;
use App\Lib\InstructorService\DescriptionImages;
use App\Lib\InstructorService\BookingIndexes;


class InstructorService extends Model
{

	use DescriptionImages, BookingIndexes;


	const DISCIPLINE_SKI = "ski";
	const DISCIPLINE_SNOWBOARD = "snowboard";

    /**
     * 
     *
     * @var array
     */
	protected $guarded = [];


    /**
     * The attributes that should be visible in arrays. (Only used in search bar)
     *
     * @var array
     */
	protected $visible = [
		"number",
		"snowboard_discipline",
		"ski_discipline",
		"instructor"
	];


	//public $disciplines = ["ski", "snowboard"];


	/**
	 * Generates an unique InstructorService number.
	 * @return int
	 */
	public static function generateNumber()
	{
		$number = rand(1000000, 9999999);
		if(self::where("number", $number)->count() == 0)
			return $number;
		else
			return self::generateNumber();
	}



	/**
	 * Find active (published) InstructorService by number
	 * @param  int $number
	 * @return null|InstructorService
	 */
	public static function findActiveByNumber($number)
	{
		return self::where([
			"number" => $number,
			"published" => true
		])->first();
	}




	/**
	 * Get the Instructor who provides this service.
	 * @return App\Instructor
	 */
	public function instructor()
	{
		return $this->belongsTo("App\Instructor");
	}


	/**
	 * Get the date ranges associated with this service.
	 * @return Illuminate\Database\Eloquent\Collection
	 */
	public function dateRanges()
	{
		return $this->hasMany("App\ServiceDateRange");
	}


	/**
	 * [reservations description]
	 * @return Illuminate\Database\Eloquent\Collection
	 */
	public function reservations()
	{
		return $this->hasMany("App\Reservation");
	}

	/**
	 * Obtain the ServiceAvailableDates associated with this service.
	 * These are used only to index the date availability for search function.
	 * @return Illuminate\Database\Eloquent\Collection
	 */
	public function availableDates()
	{
		return $this->hasMany("App\ServiceAvailableDate");
	}


	/**
	 * Obtain all the ServiceBlockedTimeblock (defined by the instructor) associated with this service.
	 * @return Illuminate\Database\Eloquent\Collection
	 */
	public function blockedTimeblocks()
	{
		return $this->hasMany("App\ServiceBlockedTimeblock");
	}



	/**
	 * Scope a query to only include active instructor services.
	 * @param  [type] $query [description]
	 * @return [type]        [description]
	 */
	public function scopeActive($query) {
		return $query->where("published", true);
	}



	/**
	 * Get instructor service images as array of assoc array with "img" and "thumbnail"
	 * @return array
	 */
	public function images()
	{
		return json_decode($this->images_json, true);
	}



	/**
	 * [featuresArray description]
	 * @return [type] [description]
	 */
	public function featuresArray()
	{
		return preg_split("/\r\n|\n|\r/", $this->features);
	}



	/**
	 * Check whether this service has the necessary information in order to be published.
	 * The information is: description, features, sport discipline, at least 1 image, at least 1 date range, and type of public.
	 * 
	 * @return boolean
	 */
	/*public function canBePublished()
	{
		if(!$this->description || !$this->features)
			return false;

		if(!$this->snowboard_discipline && !$this->ski_discipline)
			return false;

		if(!$this->images_json || sizeof($this->images()) < 1)
			return false;

		if(!$this->offered_to_adults && !$this->offered_to_kids)
			return false;

		if($this->dateRanges()->count() == 0)
			return false;

		return true;
	}*/



	/**
	 * Get an array of the total surcharge percentages to the cost of the classes according to the ammount of students.
	 * @return array
	 */
	public function getGroupSurcharges()
	{
		$surcharges[1] = 0;

		if(!$this->allows_groups)
			return $surcharges;

		else {
			for($i=2; $i<=$this->max_group_size; $i++) {
				$surcharges[$i] = floatval($this->{"person".$i."_surcharge"});
			}
		}

		return $surcharges;
	}



	/**
	 * Checks if a service is not offered in any of the days within a date range.
	 * If no current date range period intersect with the given period, returns true, otherwise, false.
	 * Start and end dates must belong to the same year.
	 * 
	 * @param  string  $date_start Y-m-d
	 * @param  string  $date_end   Y-m-d
	 * @return boolean
	 */
	public function isNotOfferedWithinDates($dateStart, $dateEnd)
	{
		
		if(Dates::getYear($dateStart) != Dates::getYear($dateEnd)) {
			throw new \Exception("Start and end date must belong to the same year.");
		}

		$queryDates = Dates::dateRangeToArray($dateStart, $dateEnd);

		
		$offeredDateRanges = $this->dateRanges()->whereYear("date_start", Dates::getYear($dateStart))->get();

		foreach($offeredDateRanges as $offeredDateRange) {

			$offeredDates = Dates::dateRangeToArray($offeredDateRange->date_start, $offeredDateRange->date_end);

			if(count(array_intersect($queryDates, $offeredDates)) > 0)
				return false;

		}

		return true;
	}



	/**
	 * Obtain the ServiceDateRange where the given date belongs (if it exists)
	 * @param  Carbon\Carbon  $date
	 * @return ServiceDateRange|null
	 */
	public function getDateRangeOfDate($date)
	{
		return $this->dateRanges()->where([
			["date_start", "<=", $date->format("Y-m-d")],
			["date_end", ">=", $date->format("Y-m-d")]
		])->first();
	}



	/**
	 * [getPricePerBlockOnDate description]
	 * @param  Carbon\Carbon $date
	 * @return float
	 */
	public function getPricePerBlockOnDate($date)
	{
		$dateRange = $this->getDateRangeOfDate($date);

		if(!$dateRange)
			return null;

		return floatval($dateRange->price_per_block);
	}



	/**
	 * Check whether the instructor service has its working hours split in half: not consecutive from beginning to end.
	 * @return boolean
	 */
	public function hasSplitWorkHours()
	{
		if($this->worktime_alt_hour_start && $this->worktime_alt_hour_end)
			return true;

		return false;
	}




	/**
	 * Check whether an hour range is included in the working hours range set by the instructor.
	 * @param  int $hour_start
	 * @param  int $hour_end
	 * @return boolean
	 */
	public function hourRangeWithinWorkingHours($hour_start, $hour_end)
	{
		if(!$this->hasSplitWorkHours()) {

			if($this->worktime_hour_start <= $hour_start && $this->worktime_hour_end >= $hour_end) {
				return true;
			}
		}
		else {
			
			if(($this->worktime_hour_start <= $hour_start && $this->worktime_hour_end >= $hour_end) ||
				($this->worktime_alt_hour_start <= $hour_start && $this->worktime_alt_hour_end >= $hour_end)) {
				return true;
			}
		}
		return false;
	}


	/**
	 * Check if this service has a given hour range of a given date occupied by other active reservation/s.
	 * 
	 * @param  Carbon\Carbon $date      
	 * @param  int $hour_start
	 * @param  int $hour_end 
	 * @return boolean
	 */
	public function hasTimeOccupiedByReservation($date, $hour_start, $hour_end)
	{
		
		if(!Reservations::validHourWorkingPeriod($hour_start, $hour_end))
			throw new \Exception("Invalid working hour period.");

		$reservations = $this->reservations()->active()
			->where("reserved_class_date", $date->format("Y-m-d"))
			->where(function ($query) use ($hour_start, $hour_end) {

				$query->where("reserved_time_start", $hour_start)
					->orWhere("reserved_time_end", $hour_end)
					->orWhere([
						["reserved_time_start", ">", $hour_start],
						["reserved_time_start", "<",  $hour_end]
					])
					->orWhere([
						["reserved_time_end", ">", $hour_start],
						["reserved_time_end", "<",  $hour_end]
					])
					->orWhere([
						["reserved_time_start", "<", $hour_start],
						["reserved_time_end", ">",  $hour_end]
					]);

			});


		if($reservations->count() > 0)
			return true;

		return false;
	}



	/**
	 * Check whether this service has an available period of hours of a certain date to be reserved/booked.
	 * 
	 * @param  Carbon\Carbon  $date
	 * @param  int  $hour_start
	 * @param  int  $hour_end
	 * @return boolean
	 */
	public function isPeriodAvailableForReserving($date, $hour_start, $hour_end)
	{
		
		if($this->getDateRangeOfDate($date) == null) // check if the instructor works that day
			return false;
		
		if(!$this->hourRangeWithinWorkingHours($hour_start, $hour_end)) // check if the instructor works in those hours
			return false;

		if($this->hasTimeOccupiedByReservation($date, $hour_start, $hour_end)) // no existing reservations placed during the given time and date
			return false;
		
		return true;
	}



	/**
	 * Check whether this service offers any discount for group
	 * @return boolean
	 */
	/*public function hasGroupSurcharges()
	{
		for($i=2; $i<=6; $i++) {
			if($this->{"person".$i."_surcharge"} > 0)
				return true;
		}
		return false;
	}*/



	/**
	 * Check if a ServiceBlockedTimeblock associated with this service exists with the given date and time block number.
	 * It does NOT check if a certain time is not available, it checks if the instructor has stated to
	 * not to offer that time block while it was free previously.
	 *
	 * @param Carbon\Carbon $date
	 * @param int $timeBlockNumber
	 * @return boolean
	 */
	public function hasBlockedTimeblock($date, $timeBlockNumber)
	{
		$count = $this->blockedTimeblocks()->where([
			"date" => $date->format("Y-m-d"),
			"time_block" => $timeBlockNumber
		])->count();

		if($count > 0)
			return true;
		else 
			return false;
	}



}
