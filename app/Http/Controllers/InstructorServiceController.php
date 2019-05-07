<?php

namespace App\Http\Controllers;

use App\InstructorService;
use Illuminate\Http\Request;

class InstructorServiceController extends Controller
{
    

	public function showDetails($service_number)
	{
		$instructorService = InstructorService::findByNumber($service_number);

		return view("service")->with("instructorService", $instructorService);
	}



}
