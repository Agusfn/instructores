<?php

namespace App\Filters;

use Illuminate\Http\Request;

class InstructorFilters extends QueryFilters
{
	
    protected $request;

    public function __construct(Request $request)
    {

    	if(!$request->has("order"))
    		$request->merge(["order" => "date_reg_desc"]);


        $this->request = $request;
        parent::__construct($request);
    }


    public function order($term) 
    {
    	
    	if($term == "date_reg_desc") {
    		return $this->builder->orderBy("created_at", "DESC");
    	}
    	else if($term == "date_reg_asc") {
    		return $this->builder->orderBy("created_at", "ASC");
    	}
    	else if($term == "name_desc") {
    		return $this->builder->orderBy("name", "DESC")->orderBy("surname", "DESC");
    	}
    	else if($term == "name_asc") {
    		return $this->builder->orderBy("name", "ASC")->orderBy("surname", "ASC");
    	}
    	else if($term == "balance_desc") {
            // the whole query is for fetching the instructor, the relationship will be loaded in another query by eloquent later, so we only select instructor data to avoid wallet data interfering our instructor models.
    		$this->builder
                ->select("instructors.*") 
                ->leftJoin("instructor_wallets", "instructor_wallets.instructor_id", "=", "instructors.id")
                ->orderBy("instructor_wallets.balance", "DESC");
    	}
    	else if($term == "balance_asc") {
            $this->builder
                ->select("instructors.*")
                ->leftJoin("instructor_wallets", "instructor_wallets.instructor_id", "=", "instructors.id")
                ->orderBy("instructor_wallets.balance", "ASC");    	
        }

    }



    public function approval($term)
    {
    	if($term == "approved") {
    		return $this->builder->where("approved", true);
    	}
    	else if($term == "pending") {
    		return $this->builder->where("approved", false);
		}
    }

    



}