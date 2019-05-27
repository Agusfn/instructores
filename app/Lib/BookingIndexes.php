<?php
namespace App\Lib;

use Carbon\CarbonPeriod;
use App\Lib\Reservations;
use App\InstructorService;


class BookingIndexes
{


	/**
	 * The instructor service to obtain availability and price information from.
	 * @var InstructorService
	 */
	private static $service;



	/**
	 * Creates an array calendar that for a given service, stores for each day of each month of activity, the price per block and the availability of each one.
	 * This will ONLY serve as a pre-processed index of availability for the datepicker and time block selector on the service's public page, prior to the reservation process.
	 *
	 * The array has indexes for months (1-12) and each month has an array of days, with indexes of day of month. The period spans only through the annual activity period.
	 * Each day has an 'available' and 'work_day' boolean property. The later means if the instructor has included that date in any of their working days range.
	 * Also, each day has a 'blocks_available' array, with the indexes for each time block number, and the value (boolean) is if available, ONLY for the hour blocks that the instructor
	 * has chosen to work.
	 * 
	 * @param  InstructorService 	$service
	 * @return array
	 */
	public static function buildBookingCalendar(InstructorService $service)
	{
		
		self::$service = $service;

		$calendar = self::createCalendarWithOccupiedBlocks();

		$calendar = self::fillCalendarWithAvailability($calendar);
		
		return $calendar;
	}




	/**
	 * Creates the calendar array with only the occupied (reserved) time blocks, of only the days that have reservations.
	 * @param InstructorService $service
	 * @return array
	 */
	private static function createCalendarWithOccupiedBlocks()
	{
		$calendar = [];

		$seasonReservations = self::$service->reservations()->active()->withinCurrentSeason()->orderBy("reserved_date", "asc")->get();
		
		foreach($seasonReservations as $reservation) {		

			$date = $reservation->date();

			foreach($reservation->reservedTimeBlocks() as $blockNumber) {
				$calendar[$date->month][$date->day]["blocks_available"][$blockNumber] = false;
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
	private static function fillCalendarWithAvailability($calendar)
	{
		
		$serviceWorkingPeriods = self::$service->dateRanges()->orderBy("date_start", "asc")->get();

		$seasonPeriod = CarbonPeriod::create(
			Reservations::getCurrentYearActivityStart(), 
			Reservations::getCurrentYearActivityEnd()
		);

		foreach($seasonPeriod as $date) 
		{

			if(isset($calendar[$date->month][$date->day]))
				$dateData = $calendar[$date->month][$date->day];
			else
				$dateData = [];


			$serviceDateRange = $serviceWorkingPeriods
				->where("date_start", "<=", $date->format("Y-m-d"))
				->where("date_end", ">=", $date->format("Y-m-d"))
				->first();


			
			if($serviceDateRange != null)  // working day
			{

				$dateData["working_day"] = true;

				$occupiedBlocks = 0;
				$workedBlocks = 0;

				for($hourBlock=0; $hourBlock<Reservations::blocksPerDay(); $hourBlock++)
				{
					$hours = Reservations::blockRangeToHourRange($hourBlock, $hourBlock);

					if(self::$service->hourRangeWithinWorkingHours($hours[0], $hours[1])) {

						if(!isset($dateData["blocks_available"][$hourBlock])) {
							$dateData["blocks_available"][$hourBlock] = true;
						}
						else if($dateData["blocks_available"][$hourBlock] == false) {
							$occupiedBlocks += 1;
						}

						$workedBlocks += 1;
					}
					else {
						unset($dateData["blocks_available"][$hourBlock]);
					}
				}

				if($workedBlocks == $occupiedBlocks)
					$dateData["available"] = false;
				else {
					$dateData["available"] = true;
					$dateData["ppb"] = $serviceDateRange->price_per_block;
				}
				
			}
			else {
				unset($dateData["blocks_available"]);
				$dateData["available"] = false;
				$dateData["working_day"] = false;
			}


			$calendar[$date->month][$date->day] = $dateData;
		}


		return $calendar;
	}





}