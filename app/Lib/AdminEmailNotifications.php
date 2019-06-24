<?php
namespace App\Lib;


class AdminEmailNotifications
{


	/**
	 * Return the recipients who will receive admin emails.
	 * @return object
	 */
	public static function recipients()
	{
		return [
			(object)[
				"email" => "agusfn20@gmail.com", // cambiar
				"name" => "Agustin"
			]
		];
	}




}