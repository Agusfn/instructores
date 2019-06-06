<?php

namespace App;


use Carbon\CarbonPeriod;
use App\Lib\Reservations;
use App\Lib\Helpers\Dates;
use App\Lib\BookingIndexes;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Lib\InstructorService\DescriptionImages;


class InstructorService extends Model
{

	use DescriptionImages;


    /**
     * 
     *
     * @var array
     */
	protected $guarded = [
		"number",
		"published",
		"instructor_id",
		"instructor_level",
		"images_json",
		"booking_calendar_json"
	];


	public $disciplines = ["ski", "snowboard"];


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
	public function canBePublished()
	{
		if(!$this->description || !$this->features)
			return false;

		if(!$this->snowboard_discipline && !$this->ski_discipline)
			return false;

		if(!$this->images_json || sizeof($this->images()) < 1)
			return false;

		if(!$this->offered_to_adults && !$this->offered_to_kids)
			return false;

		if($this->dateRanges->count() == 0)
			return false;

		return true;
	}



	/**
	 * Return an array indicating the percentage of discount for each additional person in the same reservation. 
	 * The key of the array is the person number, and the value is the discount percentage 0-100.
	 * @return array
	 */
	public function getGroupDiscounts()
	{
		$discounts = [1 => 0];

		if(!$this->allows_groups)
			return $discounts;

		else {
			for($i=2; $i<=$this->max_group_size; $i++) {
				$discounts[$i] = floatval($this->{"person".$i."_discount"});
			}
		}

		return $discounts;
	}



	/**
	 * Checks if a service is not offered in any of the days within a date range.
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
	 * Obtain the working date range to which a certain date belongs (if it exists)
	 * @param  Carbon\Carbon  $date
	 * @return ServiceDateRange|null
	 */
	public function getWorkingRangeOfDate($date)
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
		$dateRange = $this->getWorkingRangeOfDate($date);

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
			->where("reserved_date", $date->format("Y-m-d"))
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
		
		if($this->getWorkingRangeOfDate($date) == null) // check if the instructor works that day
			return false;
		
		if(!$this->hourRangeWithinWorkingHours($hour_start, $hour_end)) // check if the instructor works in those hours
			return false;

		if($this->hasTimeOccupiedByReservation($date, $hour_start, $hour_end)) // no existing reservations placed during the given time and date
			return false;
		
		return true;
	}






	public function rebuildAvailabilityIndexes()
	{

	}


	/**
	 * Rebuilds the booking calendar json (stored in booking_calendar_json).
	 * The json stores, for each day of each month of activity of the current year, the price per block and the availability of each block.
	 * This json will serve as a pre-processed index for the datepicker on the service page.
	 * 
	 * @return null
	 */
	public function rebuildJsonBookingCalendar()
	{
		$calendar = BookingIndexes::buildBookingCalendar($this);

		$this->booking_calendar_json = json_encode($calendar);
		$this->save();
	}



	/**
	 * Gets a reduced booking calendar as an array, adapted for use on the date picker. 
	 * The calendar includes only the availability and the block price for each day.
	 * 
	 * @return array
	 */
	public function getAvailabilityAndPricePerDay()
	{
		$calendar = json_decode($this->booking_calendar_json, true);

		foreach($calendar as $monthIndex => $monthData) {

			foreach($monthData as $dayIndex => $dayData) {

				unset($calendar[$monthIndex][$dayIndex]["working_day"]);

				if(!$dayData["available"]) {
					unset($calendar[$monthIndex][$dayIndex]["ppb"]);
					unset($calendar[$monthIndex][$dayIndex]["blocks_available"]);
				}

			}

		}

		return $calendar;
	}


	/**
	 * Deletes all the available dates registries of this service and creates them all again with the data of the json calendar, WHICH MUST BE UPDATED FIRST.
	 * Makes an index of all the days that the instructor does offer their service WITHIN the annual activity period (season).
	 * The index will be used exclusively for the search function.
	 * This method has to be called every time a reservation is made or cancelled, or each time the instructor makes a change to its working time tables.
	 * 
	 * @return null
	 */
	public function rebuildAvailableDatesIndex()
	{
		
		DB::table("service_available_dates")->where("instructor_service_id", $this->id)->delete();

		
		$calendar = json_decode($this->booking_calendar_json, true);

		foreach($calendar as $monthIndex => $monthData) {

			foreach($monthData as $dayIndex => $dayData) {

				if($dayData["available"] == true) {

					DB::table("service_available_dates")->insert([
						"instructor_service_id" => $this->id,
						"date" => date("Y")."-".$monthIndex."-".$dayIndex
					]);

				}

			}

		}


	}






}
