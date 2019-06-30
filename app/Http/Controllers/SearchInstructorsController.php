<?php

namespace App\Http\Controllers;


use App\Quote;
use Carbon\Carbon;
use App\InstructorService;
use App\Lib\PaymentMethods;
use Illuminate\Http\Request;
use App\Http\Validators\SearchInstructors;

//use Illuminate\Support\Facades\DB;


class SearchInstructorsController extends Controller
{
    
	
	/**
	 * Show search page.
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function search(Request $request)
	{
		$validator = new SearchInstructors($request);
		
		if($validator->fails())
			return redirect()->route("home");

		return view("search")->with("searchParams", $request->all());
	}



	/**
	 * Obtain search results with a http post request (ajax).
	 * @return [type] [description]
	 */
	public function getResults(Request $request)
	{
		
		$validator = new SearchInstructors($request);
		
		if($validator->fails())
			return response("Invalid parameters.", 422);


		$date = Carbon::createFromFormat("d/m/Y", $request->date);
		
		$adults = $request->qty_adults > 0 ? true : false;
		$kids = $request->qty_kids > 0 ? true : false;
		$totalPpl = $request->qty_adults + $request->qty_kids;
		$group = $totalPpl > 1 ? true : false;

		
		$instructorServices = InstructorService::active()->leftJoin(
			"service_available_dates", 
			"instructor_services.id", 
			"=", 
			"service_available_dates.instructor_service_id"
		);

		if($request->sort == "lower_price") {
			$instructorServices = $instructorServices->leftJoin(
				"service_date_ranges",
				"instructor_services.id",
				"=",
				"service_date_ranges.instructor_service_id"
			)->where("service_date_ranges.date_start", "<=", $date->format("Y-m-d"))
			->where("service_date_ranges.date_end", ">=", $date->format("Y-m-d"));
		}

		$instructorServices = $instructorServices->where("service_available_dates.date", $date->format("Y-m-d"));

		
		
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


		if($request->sort == "lower_price") {
			$instructorServices->orderBy("service_date_ranges.price_per_block", "ASC");
		}


		$paginatedResults = $instructorServices->with("instructor")->paginate(10);

		
		return $this->appendQuoteToEachResult($request, $paginatedResults, $date);
	}




	/**
	 * Make a quote with each instructorservice present in the pagination results, using the current search terms as the quote properties,
	 * and the quote price to each one. Then returns the results as an array.
	 * 
	 * @param  Illuminate\Http\Request $request
	 * @param  \Illuminate\Contracts\Pagination\LengthAwarePaginator $paginatedResults
	 * @param  Carbon\Carbon $date 	date to search
	 * @return array
	 */
	private function appendQuoteToEachResult($request, $paginatedResults, $date)
	{

		$services = $paginatedResults->items(); // full object results array
		$resultsArray = $paginatedResults->toArray(); // InstructorServices in array format to be returned as json, with sensible properties hidden.


		for($i=0; $i<sizeof($resultsArray["data"]); $i++) {

			$quote = new Quote($services[$i]);
			
			$quote->set(
				$request->discipline, 
				PaymentMethods::CODE_MERCADOPAGO, 
				$date, 
				$request->qty_adults, 
				$request->qty_kids, 
				0, 0 // Quote a single time block in length.
			); 

			$quote->calculate();
			$resultsArray["data"][$i]["quote"]["classes_price"] = $quote->classesPrice;
			$resultsArray["data"][$i]["quote"]["person_amt"] = $quote->personAmmount;
		}

		return $resultsArray;
	}



}
