<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClaimMessage extends Model
{
    
	protected $guarded = [];

	protected $dates = ["sent_at"];

	public $timestamps = false;




	/**
	 * Obtain the claim to which this message belongs.
	 * @return App\Claim
	 */
	public function claim()
	{
		return $this->belongsTo("App\Claim");
	}


	public function user()
	{
		return $this->belongsTo("App\User");
	}


	public function instructor()
	{
		return $this->belongsTo("App\Instructor");
	}

	public function admin()
	{
		return $this->belongsTo("App\Admin");
	}



	/**
	 * Check whether the message was made/sent by a user.
	 * @return boolean
	 */
	public function madeByUser()
	{
		return $this->made_by == "user";
	}

	/**
	 * Check whether the message was made/sent by an instructor.
	 * @return boolean
	 */
	public function madeByInstructor()
	{
		return $this->made_by == "instructor";
	}

	/**
	 * Check whether the message was made/sent by an admin.
	 * @return boolean
	 */
	public function madeByAdmin()
	{
		return $this->made_by == "admin";
	}


	/**
	 * [author description]
	 * @return App\User|App\Instructor|App\Admin
	 */
	public function author()
	{
		if($this->madeByUser()) {
			return $this->user;
		}
		else if($this->madeByInstructor()) {
			return $this->instructor;
		}
		else if($this->madeByAdmin()) {
			return $this->admin;
		}
	}



}
