<?php
namespace App\Lib\InstructorService;



class DateRangeCollection
{


	/**
	 * Collection of ServiceDateRange
	 * @var Illuminate\Database\Eloquent\Collection
	 */
	private $dateRanges;


	/**
	 * Last checked date with dateExists()
	 * @var [type]
	 */
	private $lastCheckedDate;


	/**
	 * [$lastDateRange description]
	 * @var [type]
	 */
	private $lastDateRange;


	/**
	 * @param Illuminate\Database\Eloquent\Collection $collection collection of ServiceDateRange
	 */
	public function __construct($dateRanges)
	{
		$this->dateRanges = $dateRanges;
	}


	/**
	 * Check if the date belongs to any ServiceDateRange in the collection, and also save that particular ServiceDateRange.
	 * @param Carbon\Carbon $date
	 * @return boolean
	 */
	public function dateExists($date)
	{
		$this->lastCheckedDate = $date;

		$this->lastDateRange = $this->dateRanges->filter(function($dateRange) use ($date) {
			return $dateRange->date_start->lte($date) && $dateRange->date_end->gte($date);
		})->first();

		return !($this->lastDateRange == null);
	}


	/**
	 * [getPricePerBlockOfDate description]
	 * @param  Carbon\Carbon $date
	 * @return [type]       [description]
	 */
	public function getPricePerBlockOfLastDate()
	{
		return $this->lastDateRange->price_per_block;
	}


}