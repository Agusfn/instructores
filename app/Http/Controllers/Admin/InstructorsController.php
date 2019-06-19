<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\Instructor;
use Illuminate\Http\Request;
use Mail\InstructorApproved;
use Mail\InstructorDocsRejected;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;


class InstructorsController extends Controller
{
  	
  	public function __construct()
	{
		$this->middleware('auth:admin');
	}


	/**
	 * Show existing instructor list.
	 * @return [type] [description]
	 */
	public function list()
	{
		$instructors = Instructor::with("wallet")->get();
		return view("admin.instructors.list")->with("instructors", $instructors);
	}



	/**
	 * Show details of instructor.
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function details($id)
	{
		$instructor = Instructor::with("service")->find($id);

		if(!$instructor)
			return redirect()->route("admin.instructors.list");

		return view("admin.instructors.details")->with([
			"instructor" => $instructor,
			"service" => $instructor ? $instructor->service : null
		]);
	}


	/**
	 * Approve an instructor, assigning their identification number and instructor level.
	 * This also creates the InstructorService and InstructorWallet belonging to them.
	 * @param  Request $request [description]
	 * @param  [type]  $id      [description]
	 * @return [type]           [description]
	 */
	public function approve(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [
			"identification_type" => "required|in:dni,passport",
			"identification_number" => "required|between:5,20|regex:/^[0-9+ -]*$/",
			"level" => "required|integer|between:1,5"
		]);

		if($validator->fails()) {
			return redirect()->back()->withErrors($validator, 'approval')->withInput();
		}

		$instructor = Instructor::find($id);

		if(!$instructor)
			return redirect()->back();


		$instructor->approve(
			$request->identification_type,
			$request->identification_number,
			$request->level
		);

		//Mail::to($instructor)->send(new InstructorApproved($instructor));

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

		$fileNames = array_merge(explode(",", $instructor->identification_imgs), explode(",", $instructor->professional_cert_imgs));
		foreach($fileNames as $fileName) {
			Storage::disk("local")->delete("instructor_documents/".$instructor->id."/".$fileName);
		}
		

		$instructor->rejectDocs();

		//Mail::to($instructor)->send(new InstructorDocsRejected($instructor));

		return redirect()->back();
	}



	public function displayDocumentImg(Request $request, $id, $filename)
	{	
		$storagePath = "instructor_documents/".$id."/".$filename;
		
		if(Storage::disk("local")->exists($storagePath)) {
			$imageContent = Storage::disk("local")->get($storagePath);
			return Image::make($imageContent)->response();
		}
		else {
			return abort(404);
		}

	}


}
