<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstructorBankAccount extends Model
{
	
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
	

}
