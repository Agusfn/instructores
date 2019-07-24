<?php

namespace App;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;

class InstructorWalletMovement extends Model
{
 
    use Filterable;
 
	const MOTIVE_RESERVATION_PAYMENT = "reservation_payment";
	const MOTIVE_COLLECTION = "collection";


	protected $guarded = [];

	protected $dates = ["date"];

	public $timestamps = false;


	public function wallet()
	{
		return $this->belongsTo("App\InstructorWallet");
	}


	public function reservation()
	{
		return $this->belongsTo("App\Reservation");
	}




}
