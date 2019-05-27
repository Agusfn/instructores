<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstructorBalanceMovement extends Model
{
 
	const MOTIVE_RESERVATION_PAYMENT = "payment";
	const MOTIVE_COLLECTION = "collection";


	protected $guarded = [];




	public function instructor()
	{
		return $this->belongsTo("App\Instructor");
	}


	public function reservation()
	{
		return $this->belongsTo("App\Reservation");
	}

}
