<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\Instructor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class InstructorsController extends Controller
{
  	
  	public function __construct()
	{
		$this->middleware('auth:admin');
	}


	public function list()
	{
		$instructors = Instructor::all();
		return view("admin.instructors.list")->with("instructors", $instructors);
	}



	public function details($id)
	{
		$instructor = Instructor::find($id);
		return view("admin.instructors.details")->with("instructor", $instructor);
	}



	public function approve(Request $request, $id)
	{
		$instructor = Instructor::find($id);

		if(!$instructor)
			return redirect()->back();


		$validator = Validator::make($request->all(), [
			"identification_type" => "required|in:dni,passport",
			"identification_number" => "required|between:5,20|regex:/^[0-9+ -]*$/"
		]);

		if($validator->fails()) {
			return redirect()->back()->withErrors($validator, 'approval')->withInput();
		}

		$instructor->approve();
		$instructor->fill($request->only("identification_type", "identification_number"));
		$instructor->save();

		// send mail

		return redirect()->back();
	}



	public function rejectDocs(Request $request, $id)
	{
		$instructor = Instructor::find($id);

		if(!$instructor)
			return redirect()->back();

		$validator = Validator::make($request->all(), [
			"reason" => "required",
		]);

		if($validator->fails()) {
			return redirect()->back()->withErrors($validator, 'doc_rejectal')->withInput();
		}

		$file_paths = array_merge(explode(",", $instructor->identification_imgs), explode(",", $instructor->professional_cert_imgs));
		Storage::delete($file_paths);

		$instructor->rejectDocs();

		// send mail

		return redirect()->back();
	}



}
