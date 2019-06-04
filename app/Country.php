<?php

namespace App;

use App\Lib\Helpers\Arrays;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    
    /**
     * Name of PK column. We will be using ISO 3166-1 alpha3 country code (eg: ARG)
     * @var string
     */
	protected $primaryKey = "code_alpha3";

	/**
	 * Disable PK being an incremental int.
	 * @var boolean
	 */
	public $incrementing = false;


	/**
	 * Country codes with priority in country lists (will be placed on top)
	 * @var array
	 */
	private static $priorities = ["ARG"];



	/**
	 * Gets an array with the country names as values and their codes as keys.
	 * @return array
	 */
	public static function getNamesAndCodes()
	{
		$countries = DB::table("countries")->select("code_alpha3 as code", "name_english")->get();
		
		$array = [];
		foreach($countries as $country) {
			$array[$country->code] = __($country->name_english);
		}

		foreach(self::$priorities as $priority) {
			Arrays::moveToTop($array, $priority);
		}
		
		return $array;
	}


	/**
	 * Returns the country code. 
	 * @return string
	 */
	public function code()
	{
		return $this->code_alpha3;
	}

}
