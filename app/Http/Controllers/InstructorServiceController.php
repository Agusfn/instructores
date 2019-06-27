<?php

namespace App\Http\Controllers;

use App\InstructorService;
use Illuminate\Http\Request;

class InstructorServiceController extends Controller
{
    

	/**
	 * Show instructor service public page.
	 * @param  int $service_number
	 * @return [type]                 [description]
	 */
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



	/**
	 * Fetch and return an instructor service json calendar given its number (with ajax POST request)
	 * @param  int $service_number
	 * @return [type]                 [description]
	 */
	public function fetchJsonCalendar($service_number)
	{
		$service = InstructorService::findActiveByNumber($service_number);

		if(!$service)
			return response("Invalid service number.", 422);

		return response()->json($service->getCalendarForDatepicker());

	}



}
