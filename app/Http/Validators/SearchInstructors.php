<?php

namespace App\Http\Validators;

use App\Http\Validators\Validator;

class SearchInstructors extends Validator
{
    
    public static $rules = array(
        "sort" => "required|in:default,lower_price,popular",
        "discipline" => "required|in:ski,snowboard",
        "date" => "required|date_format:d/m/Y",
        "qty_adults" => "required|integer|between:0,6",
        "qty_kids" => "required|integer|between:0,6"
    );


    public function fails()
    {
    	if(parent::fails())
    		return true;

        if($this->request->qty_adults + $this->request->qty_kids > 6 || 
            $this->request->qty_adults + $this->request->qty_kids < 1) {
            $this->messages->add("qty_adults", "La cantidad de personas total debe ser entre 1 y 6.");
            return true;
        }
        
    }

}