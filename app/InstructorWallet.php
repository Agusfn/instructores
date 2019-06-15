<?php

namespace App;

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



}
