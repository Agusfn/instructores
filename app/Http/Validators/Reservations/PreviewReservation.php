<?php

namespace App\Http\Validators\Reservations;

use Carbon\Carbon;
use App\Lib\Reservations;
use App\InstructorService;
use App\Lib\Helpers\Dates;
use App\Http\Validators\Validator;
use Illuminate\Support\Facades\Auth;

class PreviewReservation extends Validator
{
    
    public static $rules = array(
        "date" => "required|date_format:d/m/Y",
        "persons" => "required|integer|between:1,6",
        "t_start" => "required|integer|between:0,3",
        "t_end" => "required|integer|between:0,3|gte:t_start",
        "last_price" => "numeric"
    );


    public function fails()
    {
    	if(parent::fails())
    		return true;

        $service = InstructorService::findActiveByNumber($this->request->service_number);

        $hourRange = Reservations::blockRangeToHourRange($this->request->t_start, $this->request->t_end);
        $date = Carbon::createFromFormat("d/m/Y", $this->request->date);

        if(!$service->isPeriodAvailableForReserving($date, $hourRange[0], $hourRange[1])) {
            $this->messages = "La fecha y hora seleccionadas ya no están disponibles.";
            return true;
        }

        if($this->request->persons > 1 && (!$service->allows_groups || $service->max_group_size < $this->request->persons)) {
            $this->messages = "El instructor no permite clases grupales de esta cantidad.";
            return true;
        }

        return false;
    }

}