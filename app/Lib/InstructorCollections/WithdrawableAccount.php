<?php

namespace App\Lib\InstructorCollections;

use Carbon\Carbon;
use App\InstructorCollection;
use Illuminate\Database\Eloquent\Model;

abstract class WithdrawableAccount extends Model
{

	/**
	 * Amount of days that the instructor must wait in order to make a collection request to the account.
	 */
	const LOCK_TIME_DAYS = 1;



	/**
	 * Gets the instructor who owns this account.
	 * @return App\Instructor
	 */
	public function instructor()
	{
		return $this->belongsTo("App\Instructor");
	}



	/**
	 * Get the time where this account gets unlocked to make collection requests for it.
	 * @return Carbon\Carbon
	 */
	public function unlockTime()
	{
		return $this->updated_at->addDays(self::LOCK_TIME_DAYS);
	}
	

	/**
	 * Check whether the lock time days have passed after the last update of the account.
	 * @return boolean
	 */
	public function lockTimePassed()
	{
		if((new Carbon())->greaterThan($this->unlockTime()))
			return true;

		return false;
	}


	/**
	 * Check whether this account has pending collection requests.
	 * @return boolean
	 */
	public function hasPendingCollections()
	{
		return $this->collections()->pending()->count() > 0;
	}


}
