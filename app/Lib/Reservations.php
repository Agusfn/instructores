<?php

namespace App\Lib;


use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Lib\Helpers\Dates;


class Reservations
{

	/**
	 * Time in hours of start and end of the period of activity each day within the annual activity period.
	 * Instructor services can be booked/reserved within this time period.
	 * ** Should not be changed **
	 */
	const DAILY_ACTIVITY_START_TIME = 9;
	const DAILY_ACTIVITY_END_TIME = 17; // 8hs per day.


	/**
	 * Length in hours of each block of time, which is the unit of time for reservations.
	 * Classes reserved can last an integer ammount of blocks (1, 2...)
	 * 
	 * Each block is represented with an int, starting from 0 which is the first one of the day, and up.
	 * This way: 0: 9-11hs, 1: 11-13hs, 2: 13-15hs, 3: 15-17hs.
	 * 
	 * ** Should not be changed **
	 */
	const RESERVATION_BLOCK_LENGTH = 2;  // 2hs per block, 4 blocks per day.


	/**
	 * The max length in blocks of an individual reservation. *** CHECK THIS!!! ****
	 */
	const MAX_RESERVATION_LENGTH = 2; // 4hs



	/**
	 * The ammount of time blocks per day.
	 * @return int
	 */
	public static function blocksPerDay()
	{
		return (self::DAILY_ACTIVITY_END_TIME - self::DAILY_ACTIVITY_START_TIME) / self::RESERVATION_BLOCK_LENGTH;
	}



	/**
	 * Methods for the start and end of the period of annual activity. 
	 * Only within this period the instructors can offer their services.
	 * These values are stored persistenly with Setting dependency.
	 */

	/**
	 * Gets the day and month of the annual activity start date.
	 * @return string	m-d format
	 */
	public static function getActivityStartDate()
	{
		return \Setting::get("reservations.activity_start_date");
	}

	/**
	 * Sets the day and month of the annual activity start date.
	 * @param string $date 	m-d format
	 */
	public static function setActivityStartDate($date)
	{
		\Setting::set("reservations.activity_start_date", $date);
		\Setting::save();
	}

	/**
	 * Gets the day and month of the annual activity end date.
	 * @return string	m-d format
	 */
	public static function getActivityEndDate()
	{
		return \Setting::get("reservations.activity_end_date");
	}

	/**
	 * Sets the day and month of the annual activity end date.
	 * @param string $date 	m-d format
	 */
	public static function setActivityEndDate($date)
	{
		\Setting::set("reservations.activity_end_date", $date);
		\Setting::save();
	}


	/**
	 * [getCurrentYearStartDate description]
	 * @return Carbon/Carbon
	 */
	public static function getCurrentYearActivityStart()
	{
		return Carbon::parse(date("Y")."-".self::getActivityStartDate());
	}

	/**
	 * [getCurrentYearStartDate description]
	 * @return Carbon/Carbon
	 */
	public static function getCurrentYearActivityEnd()
	{
		return Carbon::parse(date("Y")."-".self::getActivityEndDate());
	}


	/**
	 * Returns the nearest day of activity from today, of current year. 
	 * If period hasn't started, returns activity start date. If period has started, returns today. If it has ended, returns null.
	 * @return Carbon\Carbon|null
	 */
	public static function nearestActivityDayCurrentYear()
	{
		$today = Carbon::today();
		$activityStart = self::getCurrentYearActivityStart();
		$activityEnd = self::getCurrentYearActivityEnd();

		if($today->lessThanOrEqualTo($activityEnd)) {
			if($activityStart->isBefore($today))
				return $today;
			else 
				return $activityStart;
		}
		else
			return null;

	}


	/**
	 * Obtain a period of all the days left until the current year activity end. 
	 * If the season hasn't started, it contains all the season days, otherwise, it contains from today to season end.
	 * @return Carbon\CarbonPeriod
	 */
	public static function periodUntilActivityEnd()
	{
		$startDate = self::nearestActivityDayCurrentYear();

		if(!$startDate)
			return []; // should be empty carbonperiod, but this is iterable too.

		return CarbonPeriod::create($startDate, self::getCurrentYearActivityEnd());
	}



	/**
	 * Returns int or int[] with the block numbers that span the given hour period.
	 * The hour period must be consistent with the block definitions above.
	 * Ex: 9hs to 15hs returns [0, 1, 2]
	 * 
	 * @param  int $hour_start
	 * @param  int $hour_end
	 * @return int[]
	 */
	public static function hourRangeToBlocks($hour_start, $hour_end)
	{
		
		if(self::validHourWorkingPeriod($hour_start, $hour_end)) {
		
			$block_ammt = ($hour_end - $hour_start) / self::RESERVATION_BLOCK_LENGTH;
			$first_block_no = ($hour_start - self::DAILY_ACTIVITY_START_TIME) / self::RESERVATION_BLOCK_LENGTH;

			$block = $first_block_no;
			$blocks = [];

			for($i=0; $i<$block_ammt; $i++) {
				$blocks[] = $block;
				$block += 1;
			}

			return $blocks;
		}

		throw new \Exception("The specified hour period is invalid.");

	}


	/**
	 * Check whether an hour period is valid following the definitions of working hours made at the top.
	 * @param  int $hour_start
	 * @param  int $hour_end
	 * @return boolean
	 */
	public static function validHourWorkingPeriod($hour_start, $hour_end)
	{
		if(is_integer($hour_start) && is_integer($hour_end) && 
			$hour_start >= self::DAILY_ACTIVITY_START_TIME && 
			$hour_end <= self::DAILY_ACTIVITY_END_TIME &&
			$hour_start < $hour_end &&
			($hour_end - $hour_start) % self::RESERVATION_BLOCK_LENGTH == 0) 
		{
			return true;
		}
		
		return false;
	}



	/**
	 * Convert a range of time block numbers (inclusive) determined by start and end, to an hour range.
	 * Eg: blocks 1, 2 => [11, 15]
	 * @param  int[] $blockNumbers
	 * @return int[]
	 */
	public static function blockRangeToHourRange($blockStart, $blockEnd)
	{
		
		$startTime = self::DAILY_ACTIVITY_START_TIME + $blockStart * self::RESERVATION_BLOCK_LENGTH;
		$endTime = self::DAILY_ACTIVITY_START_TIME + $blockEnd * self::RESERVATION_BLOCK_LENGTH + self::RESERVATION_BLOCK_LENGTH;

		return [$startTime, $endTime];
	}



	/**
	 * Convert one or more time block numbers to a readable hour range.
	 * @param  int[] $blockNumbers
	 * @return string
	 */
	public static function blocksToReadableHourRange($blockStart, $blockEnd, $compact = false)
	{
		$hourRange = self::blockRangeToHourRange($blockStart, $blockEnd);
		return Dates::hoursToReadableHourRange($hourRange[0], $hourRange[1], $compact);
	}


}