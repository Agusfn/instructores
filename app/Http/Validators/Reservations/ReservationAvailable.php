<?php

namespace App\Http\Validators\Reservations;

use Carbon\Carbon;
use App\Lib\Reservations;
use App\InstructorService;
use App\Lib\Helpers\Dates;
use App\Http\Validators\Validator;
use Illuminate\Support\Facades\Auth;

class ReservationAvailable extends Validator
{
    
    public static $rules = array(
        "discipline" => "required|string|in:ski,snowboard",
        "date" => "required|date_format:d/m/Y",
        "adults_amount" => "nullable|integer|between:0,6",
        "kids_amount" => "nullable|integer|between:0,6",
        "t_start" => "required|integer|between:0,3", // time block start (inclusive)
        "t_end" => "required|integer|between:0,3|gte:t_start", // time block end (inclusive)
        "last_price" => "numeric"
    );


    /**
     * Checks if the given instructor service (obtained with the service_number url param) does not accept a new reservation with the given date,
     * persons, and hours. 
     * @return true
     */
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

        if( ($this->request->has("adults_amount") && !$service->offered_to_adults) || ($this->request->has("kids_amount") && !$service->offered_to_kids) ) {
            $this->messages = "Se ingresó una cantidad de personas incorrecta.";
            return true;
        }

        $personAmt = ($this->request->adults_amount ?? 0) + ($this->request->kids_amount ?? 0);

        if($personAmt > 1 && (!$service->allows_groups || $service->max_group_size < $personAmt)) {
            $this->messages = "El instructor no permite clases grupales de esta cantidad.";
            return true;
        }

        return false;
    }

}