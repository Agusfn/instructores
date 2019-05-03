<?php

namespace App\Http\Controllers\Instructor;

use Validator;
use App\ServiceDateRange;
use App\Helpers\Dates;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ServiceDetailsController extends Controller
{
    


	public function __construct()
	{
		$this->middleware("auth:instructor");
	}
    


	public function index()
	{
		$instructor = Auth::user();

		return view("instructor.service")->with([
			"approved" => $instructor->isApproved(),
			"instructor" => $instructor,
			"service" => $instructor->service
		]);			
	}



	public function addDateRange(Request $request)
	{
		$instructor = Auth::user();
		$response = [];

		$validator = Validator::make($request->all(), [
			"date_start" => "required|regex:/^\d{4}-\d{2}-\d{2}$/|date", // regex is to avoid inputting different date formats.
			"date_end" => "required|regex:/^\d{4}-\d{2}-\d{2}$/|date|after_or_equal:date_start",
			"block_price" => "required|numeric"
		]);

		$date_start = $request->date_start;
		$date_end = $request->date_end;

		
		if(!$instructor->isApproved()) { // if it's approved, then it has an InstructorService associated
			$response["error_msg"] = "El instructor no está aprobado y no puede ofrecer sus servicios.";
		}
        else if ($validator->fails()) {
        	$response["error_msg"] = "Las fechas deben ser válidas y el precio debe ser numérico.";
        }
        else if($date_start < date("Y-m-d")) {
        	$response["error_msg"] = "La fecha 'desde' no puede ser anterior a hoy.";
        } 
        else if(Dates::getYear($date_start) != Dates::getYear($date_end)) {
        	$response["error_msg"] = "Ambas fechas deben ser del mismo año.";
		}
		else if(!$instructor->service->hasRangeAvailable($date_start, $date_end)) {
			$response["error_msg"] = "Alguna de las fechas del rango ingresado ya pertenece a otro rango.";
		}
		else
		{

			$dateRange = ServiceDateRange::create([
				"instructor_service_id" => $instructor->service->id,
				"date_start" => $date_start,
				"date_end" => $date_end,
				"price_per_block" => $request->block_price
			]);

			$response["range_id"] = $dateRange->id;

		}


		if(isset($response["error_msg"]))
			$response["success"] = false;
		else
			$response["success"] = true;


		return response()->json($response);


	}



	public function removeDateRange(Request $request)
	{
		$instructor = Auth::user();
		$response = [];


		if(!$request->filled("range_id") || !is_int($request->range_id)) {
			$response["error_msg"] = "Id rango de fechas incorrecto.";
		}
		else if(!$instructor->isApproved()) { // if it's approved, then it has an InstructorService associated
			$response["error_msg"] = "El instructor no está aprobado y no puede ofrecer sus servicios.";
		}
		else
		{
			$dateRange = ServiceDateRange::find($request->id);

		}


		if(isset($response["error_msg"]))
			$response["success"] = false;
		else
			$response["success"] = true;

		return response()->json($response);
	}

    
}
