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

        "payment_type" => "required|in:card,offline",
        "paymentMethodId" => "required", // visa, master, amex, cabal, rapipago, atm, ...

        "card_token" => "required_if:payment_type,card",
        "installments" => "required_if:payment_type,card|integer", // number of installments to pay in
        "issuer" => "nullable|integer", // id number of card issuer (when using non mainstream cards)

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