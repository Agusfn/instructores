<?php

namespace App\Http\Controllers\Instructor;

use Validator;

use App\ServiceDateRange;
use App\Lib\Reservations;
use App\Lib\Helpers\Dates;
use App\Lib\Helpers\Images;
use Illuminate\Http\Request;
//use App\Rules\InstructorWorkHours;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class ServiceDetailsController extends Controller
{
    

	/**
	 * [__construct description]
	 */
	public function __construct()
	{
		$this->middleware("auth:instructor")->only("index");
		$this->middleware("instructor.approved")->except("index");
	}
    


	/**
	 * [index description]
	 * @return [type] [description]
	 */
	public function index()
	{
		$instructor = Auth::user();

		return view("instructor.service")->with([
			"instructor" => $instructor,
			"service" => $instructor->service
		]);			
	}


	/**
	 * [addDateRange description]
	 * @param Request $request [description]
	 */
	public function addDateRange(Request $request)
	{
		$instructor = Auth::user();

		$validator = Validator::make($request->all(), [
			"date_start" => "required|regex:/^\d{4}-\d{2}-\d{2}$/|date", // regex is to avoid inputting different date formats.
			"date_end" => "required|regex:/^\d{4}-\d{2}-\d{2}$/|date|after_or_equal:date_start",
			"block_price" => "required|numeric"
		]);

		$date_start = $request->date_start;
		$date_end = $request->date_end;

		
		if ($validator->fails()) {
			return response($validator->messages()->first(), 422);
        }
        else if($date_start < date("Y-m-d")) {
        	return response("La fecha 'desde' no puede ser anterior a hoy.", 422);
        } 
        else if(Dates::getYear($date_start) != Dates::getYear($date_end)) {
        	return response("Ambas fechas deben ser del mismo año.", 422);
		}
		else if(!$instructor->service->hasRangeAvailable($date_start, $date_end)) {
			return response("Alguna de las fechas del rango ingresado ya pertenece a otro rango.", 422);
		}
		

		$dateRange = ServiceDateRange::create([
			"instructor_service_id" => $instructor->service->id,
			"date_start" => $date_start,
			"date_end" => $date_end,
			"price_per_block" => $request->block_price
		]);

		return response()->json(["range_id" => $dateRange->id]);
	}


	/**
	 * [removeDateRange description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function removeDateRange(Request $request)
	{
		$instructor = Auth::user();

		$validator = Validator::make($request->all(), [
			"range_id" => "required|integer",
		]);

		if ($validator->fails()) {
			return response($validator->messages()->first(), 422);
        }
	
		$dateRange = ServiceDateRange::find($request->range_id);

		if(!$dateRange || $dateRange->service->instructor->id != $instructor->id) {
			return response("Rango de fechas a eliminar inexistente.", 422);
		}

		$dateRange->delete();
		return response(200);
	}



	/**
	 * [uploadImage description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function uploadImage(Request $request) 
	{
		$instructor = Auth::user();

		$validator = Validator::make($request->all(), [
			"file" => "required|file|mimes:jpeg,png|max:4096"
		]);

		if ($validator->fails()) {
			return response($validator->messages()->first(), 422);
        }
        else if($instructor->service->images_json != null && sizeof($instructor->service->images()) >= 5) {
        	return response("Se alcanzó la cantidad máxima de imágenes subidas.", 422);
        }

        $imgNames = $instructor->service->addImage($request->file("file"));

		return response()->json(["img" => $imgNames]);
	}




	/**
	 * [deleteImage description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function deleteImage(Request $request)
	{
		$instructor = Auth::user();

		$validator = Validator::make($request->all(), [
			"file_name" => "required|string"
		]);

		if ($validator->fails()) {
			return response($validator->messages()->first(), 422);
        }
        if(!$instructor->service->hasImage($request->file_name)) {
        	return response("The image file name provided is invalid.", 422);
        }

        $instructor->service->removeImage($request->file_name);

        return response("OK",200);

	}
    



	/**
	 * [saveChanges description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function saveChanges(Request $request)
	{
		//dd($request);
		$validator = Validator::make($request->all(), [
			"description" => "required|string|min:10",
			"features" => "required|string",
			"worktime_hour_start" => "required|integer",
			"worktime_hour_end" => "required|integer",
			"worktime_alt_hour_start" => "nullable|integer|gt:worktime_hour_end",
			"worktime_alt_hour_end" => "nullable|integer"
		])->validate();

		if(!Reservations::validHourWorkingPeriod((int)$request->worktime_hour_start, (int)$request->worktime_hour_end) || 
			($request->filled("worktime_alt_hour_start") && 
			!Reservations::validHourWorkingPeriod((int)$request->worktime_alt_hour_start, (int)$request->worktime_alt_hour_end))
		)
			return redirect()->back()->withErrors("Invalid working hours.");


		$instructor = Auth::user();


		$instructor->service->fill($request->all());
		$instructor->service->allows_groups = $request->has("allow_groups");
		$instructor->service->save();

		// Poner esto ultimo en un task asi se hace mas rapido dsps
		$instructor->service->rebuildJsonBookingCalendar();
		$instructor->service->rebuildAvailableDates();

		return redirect()->back();
	}




}
