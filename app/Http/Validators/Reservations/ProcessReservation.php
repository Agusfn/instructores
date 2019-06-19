<?php

namespace App\Http\Validators\Reservations;

use App\Http\Validators\Validator;
use Illuminate\Support\Facades\Auth;

class ProcessReservation extends Validator
{
    
    public static $rules = array(
        "phone" => "between:5,25",
        "address_country" => "required",
        "address" => "required",
        "address_city" => "required",
        "address_state" => "required",
        "address_postal_code" => "required",

        "paymentMethodId" => "required", // visa/master/amex..
        "card_token" => "required",
        "installments" => "required|integer", // number of installments to pay in
        "issuer" => "nullable|integer", // id of the card issuer (when using non mainstream cards)

        "total_amount" => "required|numeric" // to check last moment price changes
    );


    public function fails()
    {
        if(parent::fails())
            return true;

        if(Auth::user()->phone_number == null && !$this->request->filled("phone")) {
            $this->messages->add("phone", "No se ingresó número de teléfono.");
            return true;
        }

        return false;
    }

}