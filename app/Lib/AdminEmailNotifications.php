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
			(object)(config("admin_notifications.recipients")[0])
		];
	}




}