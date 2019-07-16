<?php

namespace App\Http\Validators\Instructor;

use App\Lib\Reservations;
use App\Http\Validators\Validator;

class UpdateServiceData extends Validator
{
    
    public static $rules = array(
		"description" => "required|string|between:0,1500",
        "snowboard_discipline" => "required_without:ski_discipline",
        "ski_discipline" => "required_without:snowboard_discipline",
		"features" => "required|string|between:0,300",
		"worktime_hour_start" => "required|integer",
		"worktime_hour_end" => "required|integer",
		"worktime_alt_hour_start" => "integer|gt:worktime_hour_end",
		"worktime_alt_hour_end" => "required_with:worktime_alt_hour_start|integer",
        "allow_adults" => "required_without:allow_kids",
        "allow_kids" => "required_without:allow_adults",
		"max_group_size" => "required|integer|between:2,6",
		"person2_surcharge" => "required|numeric|between:0,100",
		"person3_surcharge" => "required|numeric|between:0,100",
		"person4_surcharge" => "required|numeric|between:0,100",
		"person5_surcharge" => "required|numeric|between:0,100",
		"person6_surcharge" => "required|numeric|between:0,100",
    );


    public function fails()
    {
    	if(parent::fails())
    		return true;

    	
    	// Working hours (has front end validation)
    	$validHours = Reservations::validHourWorkingPeriod(
    		(int)$this->request->worktime_hour_start, 
    		(int)$this->request->worktime_hour_end
    	);
		$validHoursAlt = Reservations::validHourWorkingPeriod(
			(int)$this->request->worktime_alt_hour_start, 
			(int)$this->request->worktime_alt_hour_end
		);

    	if(!$validHours || ($this->request->has("worktime_alt_hour_start") && !$validHoursAlt)) {
    		$this->messages = "Invalid working hours";
    		return true;
    	}


    	return false;
    }

}