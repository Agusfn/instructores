<?php

namespace App\Http\Validators\Instructor;

use App\Lib\Reservations;
use App\Http\Validators\Validator;

class UpdateServiceData extends Validator
{
    
    public static $rules = array(
		"description" => "required|string|min:10",
		"features" => "required|string",
		"worktime_hour_start" => "required|integer",
		"worktime_hour_end" => "required|integer",
		"worktime_alt_hour_start" => "nullable|integer|gt:worktime_hour_end",
		"worktime_alt_hour_end" => "nullable|required_with:worktime_alt_hour_start|integer",
		"max_group_size" => "required|integer|between:2,6",
		"person2_discount" => "required|numeric|between:0,100",
		"person3_discount" => "required|numeric|between:0,100",
		"person4_discount" => "required|numeric|between:0,100",
		"person5_discount" => "required|numeric|between:0,100",
		"person6_discount" => "required|numeric|between:0,100",
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

    	if(!$validHours || ($this->request->filled("worktime_alt_hour_start") && !$validHoursAlt)) {
    		$this->messages = "Invalid working hours";
    		return true;
    	}


    	return false;
    }

}