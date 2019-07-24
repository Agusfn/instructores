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

    }

    



}