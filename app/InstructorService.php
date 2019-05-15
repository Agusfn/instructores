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
	protected $fillable = ["description", "features", "worktime_hour_start", "worktime_hour_end", "worktime_alt_hour_start", "worktime_alt_hour_end"];



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
	 * Find InstructorService by number
	 * @param  int $number
	 * @return null|InstructorService
	 */
	public static function findByNumber($number)
	{
		return self::where("number", $number)->first();
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
	 * Check if a certain date range doesn't have any of its dates included in another range, for a certain service.
	 * Start and end dates must belong to the same year.
	 * 
	 * @param  string  $date_start Y-m-d
	 * @param  string  $date_end   Y-m-d
	 * @return boolean
	 */
	public function hasRangeAvailable($dateStart, $dateEnd)
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
	 * [featuresArray description]
	 * @return [type] [description]
	 */
	public function featuresArray()
	{
		return preg_split("/\r\n|\n|\r/", $this->features);
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




	public function rebuildAvailabilityIndexes()
	{

	}


	/**
	 * Rebuilds the booking calendar json (stored in booking_calendar_json).
	 * The json stores, for each day of each month of activity, the price per block and the availability of each block.
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
	 * Gets the booking calendar as an array, adapted for use on the date picker. 
	 * The calendar gets trimmed to only include whether a day is available and, if so, its price per block.
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
	 * Deletes all the unavailable dates registries of this service and creates them all again with the data of the json calendar.
	 * Makes an index of all the days that the instructor doesn't/can't offer their service WITHIN the annual activity period (season).
	 * The index will be used exclusively for the search function.
	 * This method has to be called every time a reservation is made/cancelled, or each time the instructor makes a change to its working time tables.
	 * 
	 * @return null
	 */
	public function rebuildAvailableDates()
	{
		
		DB::table("service_unavailable_dates")->where("instructor_service_id", $this->id)->delete();

		
		$calendar = json_decode($this->booking_calendar_json, true);

		foreach($calendar as $monthIndex => $monthData) {

			foreach($monthData as $dayIndex => $dayData) {

				if($dayData["available"] == false) {

					DB::table("service_unavailable_dates")->insert([
						"instructor_service_id" => $this->id,
						"date" => date("Y")."-".$monthIndex."-".$dayIndex
					]);

				}

			}

		}


	}


}
