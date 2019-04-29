<?php

namespace App\Http\Controllers\Admin;

use App\Instructor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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


}
