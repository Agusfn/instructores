<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstructorWalletMovement extends Model
{
 
	const MOTIVE_RESERVATION_PAYMENT = "payment";
	const MOTIVE_COLLECTION = "collection";


	protected $guarded = [];




	public function wallet()
	{
		return $this->belongsTo("App\InstructorWallet");
	}


	/*public function reservation()
	{
		return $this->belongsTo("App\Reservation");
	}*/

}
