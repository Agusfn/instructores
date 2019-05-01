<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstructorService extends Model
{
    

	/**
	 * Get the Instructor who provides this service.
	 * @return App\Instructor
	 */
	public function instructor()
	{
		return $this->belongsTo("App\Instructor");
	}


}
