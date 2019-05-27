<?php

namespace App\Http\Controllers;

use App\InstructorService;
use Illuminate\Http\Request;

class InstructorServiceController extends Controller
{
    

	public function showDetails($service_number)
	{
		$service = InstructorService::findActiveByNumber($service_number);

		if(!$service)
			return redirect()->route("home");

		return view("service")->with([
			"service" => $service,
			"instructor" => $service->instructor
		]);
	}



	public function fetchJsonCalendar($service_number)
	{
		$service = InstructorService::findActiveByNumber($service_number);

		if(!$service)
			return response("Invalid service number.", 422);

		return response()->json($service->getAvailabilityAndPricePerDay());

	}



}
