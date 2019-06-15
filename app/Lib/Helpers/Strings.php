<?php
namespace App\Lib\Helpers;

class Strings
{

	/**
	 * Splits a full name and get its first name, assuming it's the first word.
	 * @param  string $fullName
	 * @return string
	 */
	public static function getFirstName($fullName)
	{
		$names = explode(" ", $fullName);
		return $names[0];
	}

	/**
	 * Splits a full name and get its last names, assuming it's composed of all of the names except the first one.
	 * @param  string $fullName
	 * @return string
	 */
	public static function getLastName($fullName)
	{
		$names = explode(" ", $fullName);
		unset($names[0]);
		return implode(" ", $names);
	}


}