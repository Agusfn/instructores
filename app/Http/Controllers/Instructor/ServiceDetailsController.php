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



	public function pause()
	{
		$instructor = Auth::user();

		if($instructor->isApproved() && $instructor->service->published) {

			$instructor->service->published = false;
			$instructor->service->save();
		}

		return redirect()->back();
	}



	public function activate()
	{
		$instructor = Auth::user();

		if($instructor->isApproved() && !$instructor->service->published) {


			if(!$instructor->hasMpAccountAssociated())
				return redirect()->back()->withErrors([
					"cant_activate" => 'Para publicar tu servicio debés asociar tu cuenta de MercadoPago primero (en la pestaña "mis cobros").'
				]);

			if(!$instructor->service->canBePublished())
				return redirect()->back()->withErrors([
					"cant_activate" => "Debes ingresar una descripción, características, y al menos una foto y un rango de días de trabajo."
				]);
			

			$instructor->service->published = true;
			$instructor->service->save();

		}

		return redirect()->back();
	}



	/**
	 * [addDateRange description]
	 * @param Request $request [description]
	 */
	public function addDateRange(Request $request)
	{
		
		$validator = new CreateDateRange($request);

		if($validator->fails()) 
			return response($validator->messages()->first(), 422);
		

		$instructor = Auth::user();

		$dateRange = ServiceDateRange::create([
			"instructor_service_id" => $instructor->service->id,
			"date_start" => $request->date_start,
			"date_end" => $request->date_end,
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

		$validator = new UpdateServiceData($request);

		if($validator->fails()){ 
			return redirect()->back()->withErrors($validator->messages());
		}

		$service = Auth::user()->service;


		$service->fill($request->all());
		$service->allows_groups = $request->has("allow_groups");

		$service->save();


		// Poner esto ultimo en un task asi se hace mas rapido dsps
		$service->rebuildJsonBookingCalendar();
		$service->rebuildUnavailableDatesIndex();

		return redirect()->back();
	}




}
