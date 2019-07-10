<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    
	public function __construct()
	{
		$this->middleware('auth:admin');
	}


	public function index()
	{
		return redirect()->route("admin.instructors.list");
		//return view("admin.index");
	}


}
