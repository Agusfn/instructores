<?php

namespace App\Lib\Helpers;

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



}