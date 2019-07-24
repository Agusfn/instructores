<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\InstructorWalletMovement;

class InstructorWalletMovementFilters extends QueryFilters
{
	
    protected $request;

    public function __construct(Request $request)
    {

    	if(!$request->has("order"))
    		$request->merge(["order" => "date_desc"]);


        $this->request = $request;
        parent::__construct($request);
    }


    public function order($term) 
    {
    	
    	if($term == "date_desc") {
    		return $this->builder->orderBy("date", "DESC");
    	}
    	else if($term == "date_asc") {
    		return $this->builder->orderBy("date", "ASC");
    	}
    	else if($term == "amount_desc") {
    		return $this->builder->orderBy("net_amount", "DESC");
    	}
    	else if($term == "amount_asc") {
    		return $this->builder->orderBy("net_amount", "ASC");
    	}

    }

    
    public function type($term)
    {
        if($term == "credit") {
            return $this->builder->where("motive", InstructorWalletMovement::MOTIVE_RESERVATION_PAYMENT);
        }
        else if($term == "debit") {
            return $this->builder->where("motive", InstructorWalletMovement::MOTIVE_COLLECTION);
        }
    }


}