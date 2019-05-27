<?php

namespace App\Http\Validators\Instructor;

use App\Lib\Helpers\Dates;
use App\Http\Validators\Validator;
use Illuminate\Support\Facades\Auth;

class CreateDateRange extends Validator
{
    
    public static $rules = array(
        "date_start" => "required|date_format:Y-m-d", // regex is to avoid inputting different date formats.
        "date_end" => "required|date_format:Y-m-d|after_or_equal:date_start",
        "block_price" => "required|numeric"
    );


    public function fails()
    {
    	if(parent::fails())
    		return true;

        $date_start = $this->request->date_start;
        $date_end = $this->request->date_end;

        if($date_start < date("Y-m-d")) {
            $this->messages->add("date_start", "La fecha 'desde' no puede ser anterior a hoy.");
            return true;
        } 
        else if(Dates::getYear($date_start) != Dates::getYear($date_end)) {
            $this->messages->add("date_start", "Ambas fechas deben ser del mismo año.");
            return true;
        }
        else if(!Auth::user()->service->isNotOfferedWithinDates($date_start, $date_end)) {
            $this->messages->add("date_start", "Alguna de las fechas del rango ingresado ya pertenece a otro rango.");
            return true;
        }

    	return false;
    }

}