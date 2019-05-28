<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstructorMpAccount extends Model
{
    
	protected $guarded = [];



	/**
	 * Gets the instructor who owns this account.
	 * @return App\Instructor
	 */
	public function instructor()
	{
		return $this->belongsTo("App\Instructor");
	}




}
