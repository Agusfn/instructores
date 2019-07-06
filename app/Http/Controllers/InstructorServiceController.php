<?php

namespace App\Http\Controllers;

use App\Lib\Reservations;
use App\InstructorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InstructorServiceController extends Controller
{
    

	/**
	 * Show instructor service public page.
	 * @param  int $service_number
	 * @return [type]                 [description]
	 */
	public function showDetails(Request $request, $service_number)
	{
		$service = InstructorService::findActiveByNumber($service_number);

		if(!$service)
			return redirect()->route("home");


		$validator = Validator::make($request->all(), [
	        "discipline" => "required|in:ski,snowboard",
	        "date" => "required|date_format:d-m-Y",
	        "adults" => "required|integer|between:0,6",
	        "kids" => "required|integer|between:0,6"
        ]);
        $setInitialDate = !$validator->fails();

		return view("service")->with([
			"service" => $service,
			"instructor" => $service->instructor,
            "activityStartDate" => Reservations::getCurrentYearActivityStart(),
            "activityEndDate" => Reservations::getCurrentYearActivityEnd(),
            "setInitialDate" => $setInitialDate,
            "input" => $setInitialDate ? $request->all() : null
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
