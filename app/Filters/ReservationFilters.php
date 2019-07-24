<?php

namespace App\Filters;

use App\Reservation;
use App\InstructorService;
use Illuminate\Http\Request;

class ReservationFilters extends QueryFilters
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
    		return $this->builder->orderBy("created_at", "DESC");
    	}
    	else if($term == "date_asc") {
    		return $this->builder->orderBy("created_at", "ASC");
    	}
    	else if($term == "class_date_desc") {
    		return $this->builder->orderBy("reserved_class_date", "DESC");
    	}
    	else if($term == "class_date_asc") {
    		return $this->builder->orderBy("reserved_class_date", "ASC");
    	}
    	else if($term == "price_desc") {
    		return $this->builder->orderBy("final_price", "DESC");
    	}
    	else if($term == "price_asc") {
    		return $this->builder->orderBy("final_price", "ASC");
    	}

    }



    public function status($term)
    {
    	if($term == "payment_pending") {
    		return $this->builder->where("status", Reservation::STATUS_PAYMENT_PENDING);
    	}
    	else if($term == "pending_confirmation") {
    		return $this->builder->where("status", Reservation::STATUS_PENDING_CONFIRMATION);
		}
    	else if($term == "payment_failed") {
    		return $this->builder->where("status", Reservation::STATUS_PAYMENT_FAILED);
		}
    	else if($term == "rejected") {
    		return $this->builder->where("status", Reservation::STATUS_REJECTED);
		}
    	else if($term == "confirmed") {
    		return $this->builder->where("status", Reservation::STATUS_CONFIRMED);
    	}
    	else if($term == "concluded") {
    		return $this->builder->where("status", Reservation::STATUS_CONCLUDED);
		}
    	else if($term == "canceled") {
			return $this->builder->where("status", Reservation::STATUS_CANCELED);    		
    	}
    }



    public function discipline($term)
    {
    	if($term == "ski") {
    		return $this->builder->where("sport_discipline", InstructorService::DISCIPLINE_SKI);
    	}
    	else if($term == "snowboard") {
    		return $this->builder->where("sport_discipline", InstructorService::DISCIPLINE_SNOWBOARD);
		}
    }



}