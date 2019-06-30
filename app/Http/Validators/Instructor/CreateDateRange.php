<?php

namespace App\Http\Validators\Instructor;

use Carbon\Carbon;
use App\Lib\Helpers\Dates;
use App\Http\Validators\Validator;
use Illuminate\Support\Facades\Auth;

class CreateDateRange extends Validator
{
    
    public static $rules = array(
        "date_start" => "required|date_format:d/m/y",
        "date_end" => "required|date_format:d/m/y|after_or_equal:date_start",
        "block_price" => "required|numeric"
    );


    public function fails()
    {
    	if(parent::fails())
    		return true;

        $dateStart = Carbon::createFromFormat("d/m/y", $this->request->date_start);
        $dateEnd = Carbon::createFromFormat("d/m/y", $this->request->date_end);

        if($dateStart->isBefore(Carbon::now())) {
            $this->messages->add("date_start", "La fecha 'desde' no puede ser anterior o igual a hoy.");
            return true;
        } 
        else if($dateStart->year != $dateEnd->year) {
            $this->messages->add("date_start", "Ambas fechas deben ser del mismo año.");
            return true;
        }
        else if(!Auth::user()->service->isNotOfferedWithinDates($dateStart->format("Y-m-d"), $dateEnd->format("Y-m-d"))) {
            $this->messages->add("date_start", "Alguna de las fechas del rango ingresado ya pertenece a otro rango.");
            return true;
        }

    	return false;
    }

}