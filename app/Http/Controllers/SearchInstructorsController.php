<?php

namespace App\Http\Controllers;

use Validator;
use Carbon\Carbon;
use App\InstructorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchInstructorsController extends Controller
{
    
	

	public function search(Request $request)
	{
		$validator = Validator::make($request->all(), [
			"discipline" => "required|in:ski,snowboard",
			"date" => "required|date_format:d/m/Y",
			"qty_adults" => "required|integer|between:0,6",
			"qty_kids" => "required|integer|between:0,6"
		]);

		if($validator->fails())
			return redirect()->route("home");


		$date = Carbon::createFromFormat("d/m/Y", $request->date);

		

		$adults = $request->qty_adults > 0 ? true : false;
		$kids = $request->qty_kids > 0 ? true : false;
		$totalPpl = $request->qty_adults + $request->qty_kids;
		$group = $totalPpl > 1 ? true : false;

		
		$instructorServices = InstructorService::leftJoin(
			"service_available_dates", 
			"instructor_services.id", 
			"=", 
			"service_available_dates.instructor_service_id"
		)
		->where("service_available_dates.date", "2019-07-15");

		
		if($request->discipline == "ski")
			$instructorServices = $instructorServices->where("instructor_services.ski_discipline", 1);
		else if($request->discipline == "snowboard")
			$instructorServices = $instructorServices->where("instructor_services.snowboard_discipline", 1);


		if($adults)
			$instructorServices = $instructorServices->where("instructor_services.offered_to_adults", 1);
		
		if($kids)
			$instructorServices = $instructorServices->where("instructor_services.offered_to_kids", 1);
		
		if($group) {
			$instructorServices = $instructorServices->where([
				["instructor_services.allows_groups", "=", 1],
				["instructor_services.max_group_size", ">=", $totalPpl]
			]);
		}

		//dd($instructorServices->toSql());

		$instructorServices = $instructorServices->with("instructor")->get();

		
		return view("search")->with("instructorServices", $instructorServices);
	}




}
