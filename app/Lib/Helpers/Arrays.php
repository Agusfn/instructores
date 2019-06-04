<?php
namespace App\Lib\Helpers;

class Arrays
{

	/**
	 * Move an element (by key) in an array to the top.
	 * @param  [type] &$array [description]
	 * @param  [type] $key    [description]
	 * @return [type]         [description]
	 */
	public static function moveToTop(&$array, $key) {
	    $temp = array($key => $array[$key]);
	    unset($array[$key]);
	    $array = $temp + $array;
	}


}