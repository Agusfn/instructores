<?php

namespace App;

use App\InstructorCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class InstructorWallet extends Model
{
    
	protected $guarded = [];



	public function instructor()
	{
		return $this->belongsTo("App\Instructor");
	}

	public function movements()
	{
		return $this->hasMany("App\InstructorWalletMovement");
	}

	public function collections()
	{
		return $this->hasMany("App\InstructorCollection");
	}


	/**
	 * Obtain the total amount of money of all the pending collections together.
	 * @return [type] [description]
	 */
	public function pendingCollectionTotal()
	{
		$collections = DB::table("instructor_collections")->where([
			["instructor_wallet_id", "=", $this->id],
			["status", "=", InstructorCollection::STATUS_PENDING]
		])->sum("amount");

		return $collections;
	}



}
