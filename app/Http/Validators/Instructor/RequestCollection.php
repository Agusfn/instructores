<?php

namespace App\Http\Validators\Instructor;

use Carbon\Carbon;
use App\Lib\Helpers\Dates;
use App\Http\Validators\Validator;
use Illuminate\Support\Facades\Auth;

class RequestCollection extends Validator
{
    
    public static $rules = array(
        "destination" => "required|in:bank,mercadopago",
        "amount" => "required|numeric|gte:1000"
    );


    public function fails()
    {
    	if(parent::fails())
    		return true;

        $instructor = Auth::user();


        /*if(!$instructor->bankAccount->lockTimePassed()) {
            $this->messages->add("amount", "Tenés que esperar a que se desbloquee la cuenta bancaria para poder retirar dinero.");
            return true; 
        }*/


        if($this->request->amount > $instructor->wallet->balance) {
            $this->messages->add("amount", "No dispones de fondos suficientes.");
            return true;
        }


        $availableToWithdraw = $instructor->wallet->balance - $instructor->wallet->pendingCollectionTotal();

        if($this->request->amount > $availableToWithdraw) {
            $this->messages->add("amount", "Ya tenés otras solicitudes de retiro de dinero pendientes, los fondos no son suficientes.");
            return true;
        }

    	return false;
    }

}