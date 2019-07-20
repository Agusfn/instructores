<?php

namespace App\Lib\Helpers;

use Carbon\Carbon;
use \DateTime;
use \DateInterval;
use \DatePeriod;

class Dates {


	/**
	 * Converts a range of dates, determined by start and end date, to an array of individual dates (as string)
	 * @param string $date_start	Y-m-d
	 * @param string $date_end		Y-m-d
	 * @return string[]	array of dates Y-m-d
	 */
	public static function dateRangeToArray($date_start, $date_end)
	{
		$begin = new DateTime($date_start);
		$end = new DateTime($date_end);

		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($begin, $interval, $end->modify("+1 day"));

		$dates = [];
		foreach ($period as $dt) {
			$dates[] = $dt->format("Y-m-d");
		}

		return $dates;
	}


	/**
	 * Get year of Y-m-d date
	 * @param  string $date
	 * @return int
	 */
	public static function getYear($date_str) 
	{
		$date = DateTime::createFromFormat("Y-m-d", $date_str);
		return $date->format("Y");
	}


	/**
	 * Converts a range of 2 ints of hours to a readable format.
	 * Eg: 11, 13: "11:00-13:00 hs"
	 * @param int $hour1
	 * @param int $hour2
	 * @param boolean $compact
	 * @return string
	 */
	public static function hoursToReadableHourRange($hour1, $hour2, $compact = false)
	{
		if(!$compact)
			return $hour1.":00-".$hour2.":00 hs";
		else
			return $hour1."-".$hour2." hs";
	}


	/**
	 * Converts an iso 8601 date (eg: 2004-02-12T15:19:21+00:00) to MySQL date format: Y-m-d H:i:s
	 * @param string $date
	 * @return string
	 */
	public static function iso8601ToMysql($date)
	{
		return Carbon::parse($date)->setTimezone(config("app.timezone"))->format("Y-m-d H:i:s");
	}


}