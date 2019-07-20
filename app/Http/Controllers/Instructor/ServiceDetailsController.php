<?php

namespace App\Http\Controllers\Instructor;

use Validator;
use Carbon\Carbon;
use App\Lib\Reservations;
use App\ServiceDateRange;
use Illuminate\Http\Request;
use App\ServiceBlockedTimeblock;
use App\Http\Validators\Instructor\CreateDateRange;
use App\Http\Validators\Instructor\UpdateServiceData;


class ServiceDetailsController extends InstructorPanelBaseController
{
    

	/**
	 * [__construct description]
	 */
	public function __construct()
	{
		parent::__construct();
		$this->middleware("instructor.approved")->except("index");
	}
    


	/**
	 * Show instructor service details form.
	 * @return [type] [description]
	 */
	public function index()
	{
		return view("instructor.panel.service.index")->with([
			"service" => $this->instructor->service, // null if instructor not approved
			"blocksPerDay" => Reservations::blocksPerDay(),
            "activityStartDate" => Reservations::getCurrentYearActivityStart(),
            "activityEndDate" => Reservations::getCurrentYearActivityEnd()
		]);			
	}


	/**
	 * Pause an instructor service listing from being published.
	 * @return [type] [description]
	 */
	public function pause()
	{
		if($this->instructor->service->published) {

			$this->instructor->service->published = false;
			$this->instructor->service->save();
		}

		return redirect()->back();
	}



	/**
	 * Activate and publish the instructor service listing
	 * @return [type] [description]
	 */
	public function activate()
	{
		$service = $this->instructor->service;

		if($service->published)
			return redirect()->back();

		if($service->paused_by_admin) {
			return redirect()->back()->withErrors(["cant_activate" => "La publicación fue pausada por un admin, no es posible publicarla hasta que el admin la reactive."]);
		}

		if(!$service->description || !$service->features)
			return redirect()->back()->withErrors(["cant_activate" => "Escribe una descripción y las características de tu servicio antes de publicarlo."]);

		if(!$service->snowboard_discipline && !$service->ski_discipline)
			return redirect()->back()->withErrors(["cant_activate" => "Ingresa los tipos de clases que dictas (ski o snowboard) antes de publicar tu servicio."]);

		if(!$service->images_json || sizeof($service->images()) < 1)
			return redirect()->back()->withErrors(["cant_activate" => "Sube al menos una imágen de presentación de tu servicio antes de publicarlo."]);

		if(!$service->offered_to_adults && !$service->offered_to_kids)
			return redirect()->back()->withErrors(["cant_activate" => "Ingresa el tipo de público al que ofreces tu servicio antes de publicarlo."]);

		if($service->dateRanges()->count() == 0)
			return redirect()->back()->withErrors(["cant_activate" => "Ingresa al menos un rango de fechas de trabajo antes de publicar tu servicio."]);


		$this->instructor->service->published = true;
		$this->instructor->service->save();
		request()->session()->flash('activate-success');


		
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
		

        $dateStart = Carbon::createFromFormat("d/m/y", $request->date_start);
        $dateEnd = Carbon::createFromFormat("d/m/y", $request->date_end);

		$dateRange = ServiceDateRange::create([
			"instructor_service_id" => $this->instructor->service->id,
			"date_start" => $dateStart->format("Y-m-d"),
			"date_end" => $dateEnd->format("Y-m-d"),
			"price_per_block" => $request->block_price
		]);
		$this->instructor->service->rebuildAvailabilityIndexes();

		
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


		if($this->instructor->service->published && $this->instructor->service->dateRanges()->count() == 1) {
			return response("Pausa tu publicación antes de eliminar todas las fechas de trabajo.", 422);
		}
		

		$dateRange = $this->instructor->service->dateRanges()->where("range_id", $request->range_id)->first();

		if(!$dateRange) {
			return response("Rango de fechas a eliminar inexistente.", 422);
		}

		$dateRange->delete();
		$this->instructor->service->rebuildAvailabilityIndexes();

		return response(200);
	}



	/**
	 * Add a new blocked time block for the instructor service.
	 * @param Request $request [description]
	 */
	public function addBlockedTimeblock(Request $request)
	{
		$validator = Validator::make($request->all(), [
			"date" => "required|date_format:d/m/y",
			"time_block" => "required|integer|between:0,3",
		]);

		if ($validator->fails())
			return response($validator->messages()->first(), 422);


		$date = Carbon::createFromFormat("d/m/y", $request->date);

		if($this->instructor->service->getDateRangeOfDate($date) == null) {
			return response("La fecha ingresada no corresponde a un rango de fecha de trabajo.", 422);
		}

		if($this->instructor->service->hasBlockedTimeblock($date, $request->time_block)) {
			return response("El horario seleccionado del día seleccionado ya está registrado en esta lista.", 422);
		}

		$blockedTimeblock = ServiceBlockedTimeblock::create([
			"instructor_service_id" => $this->instructor->service->id,
			"date" => $date->format("Y-m-d"),
			"time_block" => $request->time_block
		]);

		$this->instructor->service->rebuildAvailabilityIndexes();

		return response()->json(["blocked_timeblock_id" => $blockedTimeblock->id]);
	}


	/**
	 * Delete a ServiceBlockedTimeblock belonging to this instructor service.
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function deleteBlockedTimeblock(Request $request)
	{
		$validator = Validator::make($request->all(), [
			"blocked_timeblock_id" => "required|integer",
		]);

		if ($validator->fails())
			return response($validator->messages()->first(), 422);

		$blockedTimeblock = $this->instructor->service->blockedTimeblocks()->where("id", $request->blocked_timeblock_id)->first();

		if(!$blockedTimeblock) {
			return response("No existe el ServiceBlockedTimeblock a eliminar.", 422);
		}

		$blockedTimeblock->delete();

		$this->instructor->service->rebuildAvailabilityIndexes();

		return response(200);
	}



	/**
	 * [uploadImage description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function uploadImage(Request $request) 
	{
		$validator = Validator::make($request->all(), [
			"file" => "required|file|mimes:jpeg,png|max:4096"
		]);

		if ($validator->fails()) {
			return response($validator->messages()->first(), 422);
        }
        else if($this->instructor->service->images_json != null && sizeof($this->instructor->service->images()) >= 5) {
        	return response("Se alcanzó la cantidad máxima de imágenes subidas.", 422);
        }

        $imgNames = $this->instructor->service->addImage($request->file("file"));

		return response()->json(["img" => $imgNames]);
	}




	/**
	 * [deleteImage description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function deleteImage(Request $request)
	{
		$validator = Validator::make($request->all(), [
			"file_name" => "required|string"
		]);

		if ($validator->fails()) {
			return response($validator->messages()->first(), 422);
        }
        if(!$this->instructor->service->hasImage($request->file_name)) {
        	return response("The image file name provided is invalid.", 422);
        }

        $this->instructor->service->removeImage($request->file_name);

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
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$service = $this->instructor->service;



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
			"person2_surcharge",
			"person3_surcharge",
			"person4_surcharge",
			"person5_surcharge",
			"person6_surcharge"
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
