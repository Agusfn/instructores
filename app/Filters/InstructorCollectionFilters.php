<?php

namespace App\Filters;

use App\InstructorCollection;
use Illuminate\Http\Request;

class InstructorCollectionFilters extends QueryFilters
{
	
    protected $request;

    public function __construct(Request $request)
    {

    	if(!$request->has("order"))
    		$request->merge(["order" => "date_req_desc"]);


        $this->request = $request;
        parent::__construct($request);
    }


    public function order($term) 
    {
    	
    	if($term == "date_req_desc") {
    		return $this->builder->orderBy("created_at", "DESC");
    	}
    	else if($term == "date_req_asc") {
    		return $this->builder->orderBy("created_at", "ASC");
    	}
    	else if($term == "amount_desc") {
    		return $this->builder->orderBy("amount", "DESC");
    	}
    	else if($term == "amount_asc") {
    		return $this->builder->orderBy("amount", "ASC");
    	}
    	

    }


    public function collect_method($term)
    {
    	if($term == "mercadopago") {
    		return $this->builder->where("destination_acc_type", InstructorCollection::DESTINATION_MP);
    	}
    	else if($term == "bank") {
    		return $this->builder->where("destination_acc_type", InstructorCollection::DESTINATION_BANK);
		}
    }

    

    public function status($term)
    {
        if($term == "pending") {
            return $this->builder->where("status", InstructorCollection::STATUS_PENDING);
        }
        else if($term == "completed") {
            return $this->builder->where("status", InstructorCollection::STATUS_COMPLETED);
        }
        else if($term == "rejected") {
            return $this->builder->where("status", InstructorCollection::STATUS_REJECTED);
        }
        else if($term == "canceled") {
            return $this->builder->where("status", InstructorCollection::STATUS_CANCELED);
        }
    }


}