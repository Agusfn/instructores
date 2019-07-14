<?php

namespace App;

use App\User;
use App\Admin;
use App\Instructor;
use App\ClaimMessage;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
 	

	const STATUS_OPEN = "open";
	const STATUS_CLOSED = "closed";


	protected $guarded = [];

	protected $dates = ["closed_at"];


	/**
	 * Generate a random claim number.
	 * @return int
	 */
	public static function generateNumber()
	{
		return random_int(100000, 999999);
	}


	/**
	 * Obtain the reservation to which this claim belongs.
	 * @return App\reservation
	 */
	public function reservation()
	{
		return $this->belongsTo("App\Reservation");
	}

	/**
	 * [messages description]
	 * @return [type] [description]
	 */
	public function messages()
	{
		return $this->hasMany("App\ClaimMessage");
	}


	/**
	 * If claim is open.
	 * @return boolean
	 */
	public function isOpen()
	{
		return $this->status == self::STATUS_OPEN;
	}


	/**
	 * If claim is closed.
	 * @return boolean
	 */
	public function isClosed()
	{
		return $this->status == self::STATUS_CLOSED;
	}



	/**
	 * Creates a new message (chat like) for this claim.
	 * @param User|Instructor|Admin 	$author
	 * @param string 					$text
	 * @return null
	 */
	public function addMessage($author, $text)
	{

		$msgData = [
			"claim_id" => $this->id,
			"sent_at" => date("Y-m-d H:i:s"),
			"text" => $text
		];

		if($author instanceof User) {
			$msgData["made_by"] = "user";
			$msgData["user_id"] = $author->id;
		}
		if($author instanceof Instructor) {
			$msgData["made_by"] = "instructor";
			$msgData["instructor_id"] = $author->id;
		}
		if($author instanceof Admin) {
			$msgData["made_by"] = "admin";
			$msgData["admin_id"] = $author->id;
		}

		ClaimMessage::create($msgData);
	}



}
