<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class InstructorBankAccount extends Model
{

	/**
	 * Amount of days that the instructor must wait in order to make collection requests, after each bank account update.
	 */
	const LOCK_TIME_DAYS = 3;


	protected $guarded = [];


	/**
	 * Returns the InstructorMpAccount with the given Mercadopago User Id.
	 * @param  int $mpUserId
	 * @return App\InstructorMpAccount|null	 
	 */
	public static function findByMpUserId($mpUserId)
	{
		return self::where("mp_user_id", $mpUserId)->first();
	}


	/**
	 * Gets the instructor who owns this account.
	 * @return App\Instructor
	 */
	public function instructor()
	{
		return $this->belongsTo("App\Instructor");
	}


	/**
	 * Get the time where this bank account is unlocked to make collection requests for it.
	 * @return Carbon\Carbon
	 */
	public function unlockTime()
	{
		return Carbon::parse($this->updated_at)->addDays(self::LOCK_TIME_DAYS);
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

}
