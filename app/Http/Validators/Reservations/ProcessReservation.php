<?php

namespace App\Http\Validators\Reservations;

use App\Http\Validators\Validator;

class ProcessReservation extends Validator
{
    
    public static $rules = array(
        "phone" => "required",
        "address_country" => "required",
        "address" => "required",
        "address_city" => "required",
        "address_state" => "required",
        "address_postal_code" => "required",

        "paymentMethodId" => "required",
        "card_token" => "required",
        "installments" => "required|integer",
        "issuer" => "", // what is this?
    );


}