<?php

namespace App\Http\Controllers;

use App\InstructorReview;
use App\Lib\Reservations;
use App\InstructorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
			"reviews" => $service->instructor->reviews()->with("user")->orderBy("created_at", "DESC")->get(),
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



	/**
	 * Make a review to an instructor, as an user.
	 * @return [type] [description]
	 */
	public function leaveReview(Request $request, $service_number)
	{

		$validator = Validator::make($request->all(), [
			"rating" => "required|integer|between:1,5",
			"comment" => "required"
		]);

		if($validator->fails()) {
			return redirect()->back()->withErrors($validator, 'review');
		}

		$service = InstructorService::findActiveByNumber($service_number);

		if(!$service || !Auth::guard("user")->check() || !Auth::user()->canLeaveReviewToInstructor($service->instructor_id))
			return redirect()->route("home");


		InstructorReview::create([
			"instructor_id" => $service->instructor_id,
			"user_id" => Auth::user()->id,
			"rating_stars" => $request->rating,
			"comment" => $request->comment
		]);

		$service->instructor->calculateReviewScore();

		return redirect()->route("service-page", $service->number);
	}



}
