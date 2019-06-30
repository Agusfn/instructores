<?php
namespace App\Lib\InstructorService;

use Log;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Lib\Reservations;
use App\InstructorService;
use Illuminate\Support\Facades\DB;

trait BookingIndexes
{



	/**
	 * Rebuild json booking calendar (used for datepicker) and date availability index (for search bar)
	 * @return null
	 */
	public function rebuildAvailabilityIndexes()
	{
		$this->rebuildJsonBookingCalendar();
		$this->rebuildAvailableDatesSearchIndex();
	}



	/**
	 * Get the calendar as an array.
	 * @return array
	 */
	private function getBookingCalendar()
	{
		return json_decode($this->booking_calendar_json, true);
	}


	/**
	 * Save the calendar as json, from array.
	 * @param  array $calendar
	 * @return null
	 */
	private function saveBookingCalendar($calendar)
	{
		$this->booking_calendar_json = json_encode($calendar);
		$this->save();
	}



	/**
	 * Gets a reduced booking calendar as an array, adapted for use on the date picker. 
	 * The calendar includes only the availability and the block price for each day.
	 * May have dates before than today, they will be ignored on client side.
	 * Also includes lowest price of all days.
	 * 
	 * @return array
	 */
	public function getCalendarForDatepicker()
	{
		$calendar = $this->getBookingCalendar();
		$lowest = 99999999;

		foreach($calendar as $monthIndex => $monthData) {

			foreach($monthData as $dayIndex => $dayData) {

				unset($calendar[$monthIndex][$dayIndex]["working_day"]);

				if(!$dayData["available"]) {
					unset($calendar[$monthIndex][$dayIndex]["ppb"]);
					unset($calendar[$monthIndex][$dayIndex]["block_availability"]);
				} else {
					if($dayData["ppb"] < $lowest)
						$lowest = $dayData["ppb"];
				}

			}

		}

		return [
			"calendar" => $calendar,
			"lowest_price" => $lowest
		];
	}




	/**
	 * Creates an array calendar that for a given service, stores for each day of each month of activity, the price per block and the availability of each one (ignores passed days)
	 * This will ONLY serve as a pre-processed index of availability for the datepicker and time block selector on the service's public page, prior to the reservation process.
	 *
	 * The array has indexes for months (1-12) and each month has an array of days, with indexes of day of month. The period spans only through the annual activity period.
	 * Each day has an 'available' and 'work_day' boolean property. The later means if the instructor has included that date in any of their working days range.
	 * Also, each day has a 'block_availability' array, with the indexes for each time block number, and the value (boolean) is if available, ONLY for the hour blocks that the instructor
	 * has chosen to work.
	 * 
	 * @param  InstructorService 	$service
	 * @return array
	 */
	private function rebuildJsonBookingCalendar()
	{
		$calendar = $this->createCalendarWithOccupiedBlocks();

		$calendar = $this->fillCalendarWithAvailability($calendar);

		$this->saveBookingCalendar($calendar);
	}




	/**
	 * Creates the calendar array with only the occupied (reserved) time blocks, of only the days that have reservations.
	 * @param InstructorService $service
	 * @return array
	 */
	private function createCalendarWithOccupiedBlocks()
	{
		$calendar = [];

		$seasonReservations = $this->reservations()->active()->leftWithinCurrentSeason()->orderBy("reserved_class_date", "asc")->get();
		
		foreach($seasonReservations as $reservation) {		

			$date = $reservation->reserved_class_date;

			foreach($reservation->reservedTimeBlocks() as $blockNumber) {
				$calendar[$date->month][$date->day]["block_availability"][$blockNumber] = false;
			}

		}

		return $calendar;
	}



	/**
	 * Adds the rest of the information for each day inside the annual activity period. This information is the 'available' and 'working_day' values,
	 * and also adds the available hour blocks, removing the occupied hour blocks in which the instructor does not work currently, if there was any added in the prev step.
	 * 
	 * @param  array            $calendar
	 * @return array
	 */
	private function fillCalendarWithAvailability($calendar)
	{
		
		$serviceWorkingPeriods = $this->dateRanges()->orderBy("date_start", "asc")->get();

		$today = Carbon::today();
		$periodStart = Reservations::getCurrentYearActivityStart();

		$seasonPeriod = CarbonPeriod::create(
			$periodStart->isBefore($today) ? $today : $periodStart, 
			Reservations::getCurrentYearActivityEnd()
		);

		foreach($seasonPeriod as $date) 
		{

			if(isset($calendar[$date->month][$date->day]))
				$dateData = $calendar[$date->month][$date->day];
			else
				$dateData = [];


			// obtain the DateRange where the current $date belongs to
			$serviceDateRange = $serviceWorkingPeriods->filter(function($dateRange) use ($date) {
				return $dateRange->date_start->lte($date) && $dateRange->date_end->gte($date);
			})->first();


			
			if($serviceDateRange != null)  // working day
			{

				$dateData["working_day"] = true;
				$dateData["ppb"] = round($serviceDateRange->price_per_block, 2);

				$occupiedBlocksAmt = 0;
				$workedBlocksAmt = 0;

				for($hourBlock=0; $hourBlock<Reservations::blocksPerDay(); $hourBlock++)
				{
					$hours = Reservations::blockRangeToHourRange($hourBlock, $hourBlock);

					if($this->hourRangeWithinWorkingHours($hours[0], $hours[1])) {

						if(!isset($dateData["block_availability"][$hourBlock])) {
							$dateData["block_availability"][$hourBlock] = true;
						}
						else if($dateData["block_availability"][$hourBlock] == false) {
							$occupiedBlocksAmt += 1;
						}

						$workedBlocksAmt += 1;
					}
					else {
						unset($dateData["block_availability"][$hourBlock]);
					}
				}

				if($workedBlocksAmt == $occupiedBlocksAmt)
					$dateData["available"] = false;
				else {
					$dateData["available"] = true;
				}
				
			}
			else {
				unset($dateData["block_availability"]);
				$dateData["available"] = false;
				$dateData["working_day"] = false;
			}


			$calendar[$date->month][$date->day] = $dateData;
		}


		return $calendar;
	}






	/**
	 * Occupy one or more time blocks in a given date in the instructorservice availability json calendar, and if the date becomes unavailable, remove it from the availability date index.
	 * @param  int $day    1-28/29/30/31
	 * @param  int $month  1-12
	 * @param  array $blocks 	array of ints
	 * @return null
	 */
	public function occupyBlocksInIndexes($month, $day, $blocks)
	{
		$calendar = $this->getBookingCalendar();

		if(!isset($calendar[$month][$day])) {
			Log::error("Could not find date (day:".$day.", month: ".$month.") in instructorservice id ".$this->id." booking calendar to occupy a booking.");
			return;
		}

		$dayData = $calendar[$month][$day];

		if(!$dayData["available"]) {
			Log::error("Date (day:".$day.", month: ".$month.") not available in booking calendar (instructorservice id ".$this->id.") when trying to occupy booking.");
			return;
		}

		foreach($blocks as $blockNumber) {
			if(isset($dayData["block_availability"][$blockNumber]) && $dayData["block_availability"][$blockNumber] == true) {
				$dayData["block_availability"][$blockNumber] = false;
			}
			else {
				Log::error("Time block ".$blockNumber." of date (day:".$day.", month: ".$month.") not available/non existent in booking calendar (instructorservice id ".$this->id.") when trying to occupy booking.");
			}
		}

		$allOccupied = true;
		foreach($dayData["block_availability"] as $blockAvailable) {
			if($blockAvailable) {
				$allOccupied = false;
				break;
			}
		}

		if($allOccupied) {
			$dayData["available"] = false;
			$this->removeDateFromSearchIndex($month, $day);
		}


		$calendar[$month][$day] = $dayData;


		$this->saveBookingCalendar($calendar);
	}




	/**
	 * Unocuppy one or more time blocks in a given date in the instructorservice availability json calendar.
	 * @param  int $day    1-28/29/30/31
	 * @param  int $month  1-12
	 * @param  array $blocks 	array of ints
	 * @return null
	 */
	public function unoccupyBlocksInIndexes($month, $day, $blocks)
	{
		$calendar = $this->getBookingCalendar();

		if(!isset($calendar[$month][$day])) {
			Log::error("Could not find date (day:".$day.", month: ".$month.") in instructorservice id ".$this->id." booking calendar to occupy a booking.");
			return;
		}

		$dayData = $calendar[$month][$day];

		if(!$dayData["working_day"]) {
			Log::error("Date (day:".$day.", month: ".$month.") not working day (instructorservice id ".$this->id.") when trying to unoccupy booking.");
			return;
		}

		foreach($blocks as $blockNumber) {
			if(isset($dayData["block_availability"][$blockNumber]) && $dayData["block_availability"][$blockNumber] == false) {
				$dayData["block_availability"][$blockNumber] = true;
			}
			else {
				Log::error("Time block ".$blockNumber." of date (day:".$day.", month: ".$month.") is already available/is non existent in booking calendar (instructorservice id ".$this->id.") when trying to unoccupy booking.");
			}
		}

		if($dayData["available"] == false)
		{
			foreach($dayData["block_availability"] as $blockAvailable) {
				if($blockAvailable) {
					$dayData["available"] = true;
					$this->addDateToSearchIndex($month, $day);
					break;
				}
			}			
		}


		$calendar[$month][$day] = $dayData;


		$this->saveBookingCalendar($calendar);
	}





	/**
	 * Deletes the date availability search index and creates them all again with the data of the json calendar, WHICH MUST BE UPDATED FIRST.
	 * Makes an index of all the days that the instructor does offer their service WITHIN the annual activity period (season).
	 * The index will be used exclusively for the search function.
	 * This method has to be called every time a reservation is made or cancelled, or each time the instructor makes a change to its working time tables.
	 * 
	 * @return null
	 */
	private function rebuildAvailableDatesSearchIndex()
	{
		
		DB::table("service_available_dates")->where("instructor_service_id", $this->id)->delete();

		
		$calendar = $this->getBookingCalendar();

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


	/**
	 * Remove a date from the availability search index.
	 * @param  int $day    1-28/29/30/31
	 * @param  int $month  1-12
	 * @return null
	 */
	private function removeDateFromSearchIndex($month, $day) 
	{
		DB::table('service_available_dates')->where([
			"instructor_service_id" => $this->id,
			"date" => date("Y")."-".$month."-".$day
		])->delete();
	}


	/**
	 * Remove a date from the availability search index.
	 * @param  int $day    1-28/29/30/31
	 * @param  int $month  1-12
	 * @return null
	 */
	private function addDateToSearchIndex($month, $day) 
	{
		try
		{
			DB::table('service_available_dates')->insert([
				"instructor_service_id" => $this->id,
				"date" => date("Y")."-".$month."-".$day
			]);
		}
		catch(\Illuminate\Database\QueryException $e)
		{
			Log::error("Error trying to add new date to instructor date availability search index. Instructorservice id ".$this->id.", day ".$day.", month ".$month.". Error msg: ".$e->getMessage());
		}

	}


 






}