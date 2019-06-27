<?php

namespace App\Http\Controllers\Instructor;

use Validator;

use App\ServiceDateRange;

use Carbon\Carbon;
use App\Lib\Helpers\Images;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
//use App\Rules\InstructorWorkHours;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Validators\Instructor\CreateDateRange;
use App\Http\Validators\Instructor\UpdateServiceData;


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
	 * Show instructor service details form.
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
	 * Pause an instructor service listing from being published.
	 * @return [type] [description]
	 */
	public function pause()
	{
		$instructor = Auth::user();

		if($instructor->service->published) {

			$instructor->service->published = false;
			$instructor->service->save();
		}

		return redirect()->back();
	}



	/**
	 * Activate and publish the instructor service listing
	 * @return [type] [description]
	 */
	public function activate()
	{
		$instructor = Auth::user();

		if(!$instructor->service->published) {

			if(!$instructor->service->canBePublished())
				return redirect()->back()->withErrors([
					"cant_activate" => "Debes ingresar la información de tu servicio (descripción, disciplina, características, alguna foto, un rango de días de trabajo, y público) para activar la publicación."
				]);

			$instructor->service->published = true;
			$instructor->service->save();
			request()->session()->flash('activate-success');

		}

		
		return redirect()->back();
	}



	/**
	 * Create an instructor service date range with it's price per time block.
	 * @param Request $request [description]
	 */
	public function addDateRange(Request $request)
	{
		
		$validator = new CreateDateRange($request);

		if($validator->fails()) 
			return response($validator->messages()->first(), 422);
		

		$instructor = Auth::user();

        $dateStart = Carbon::createFromFormat("d/m/Y", $request->date_start);
        $dateEnd = Carbon::createFromFormat("d/m/Y", $request->date_end);

		$dateRange = ServiceDateRange::create([
			"instructor_service_id" => $instructor->service->id,
			"date_start" => $dateStart->format("Y-m-d"),
			"date_end" => $dateEnd->format("Y-m-d"),
			"price_per_block" => $request->block_price
		]);
		$instructor->service->rebuildAvailabilityIndexes();

		
		return response()->json(["range_id" => $dateRange->id]);
	}



	/**
	 * Delete an instructor service date range.
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function deleteDateRange(Request $request)
	{
		$validator = Validator::make($request->all(), [
			"range_id" => "required|integer",
		]);

		if ($validator->fails())
			return response($validator->messages()->first(), 422);

		$instructor = Auth::user();

		if($instructor->service->published && $instructor->service->dateRanges()->count() == 1) {
			return response("Pausa tu publicación antes de eliminar todas las fechas de trabajo.", 422);
		}
		

		$dateRange = $instructor->service->dateRanges()->where("range_id", $request->range_id)->first();

		if(!$dateRange) {
			return response("Rango de fechas a eliminar inexistente.", 422);
		}

		$dateRange->delete();
		$instructor->service->rebuildAvailabilityIndexes();

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

		$validator = new UpdateServiceData($request);

		if($validator->fails()){ 
			return redirect()->back()->withErrors($validator->messages());
		}

		$service = Auth::user()->service;



		if($request->worktime_hour_start != $service->worktime_hour_start || $request->worktime_hour_end != $service->worktime_hour_end
			|| $request->worktime_alt_hour_start != $service->worktime_alt_hour_start || $request->worktime_alt_hour_end != $service->worktime_alt_hour_end)
		{
			$workingHoursChanged = true;
		} else {
			$workingHoursChanged = false;
		}


		$service->fill($request->only([
			"description",
			"features",
			"worktime_hour_start",
			"worktime_hour_end",
			"max_group_size",
			"person2_discount",
			"person3_discount",
			"person4_discount" ,
			"person5_discount",
			"person6_discount"
		]));

		$service->fill([
			"worktime_alt_hour_start" => $request->worktime_alt_hour_start,
			"worktime_alt_hour_end" => $request->worktime_alt_hour_end,
			"snowboard_discipline" => $request->has("snowboard_discipline"),
			"ski_discipline" => $request->has("ski_discipline"),
			"offered_to_adults" => $request->has("allow_adults"),
			"offered_to_kids" => $request->has("allow_kids"),
			"allows_groups" => $request->has("allow_groups")
		]);

		$service->save();


		if($workingHoursChanged) {
			$service->rebuildAvailabilityIndexes();
		}


		return redirect()->back();
	}




}
