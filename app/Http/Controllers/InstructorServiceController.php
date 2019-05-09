<?php

namespace App\Http\Controllers;

use App\InstructorService;
use Illuminate\Http\Request;

class InstructorServiceController extends Controller
{
    

	public function showDetails($service_number)
	{
		$service = InstructorService::findByNumber($service_number);

		if(!$service)
			return redirect()->route("home");

		return view("service")->with([
			"service" => $service,
			"instructor" => $service->instructor
		]);
	}



}
