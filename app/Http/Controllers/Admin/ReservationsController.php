<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReservationsController extends Controller
{
  	public function __construct()
	{
		$this->middleware('auth:admin');
	}


	public function list()
	{
		return view("admin.reservations.list");
	}

}
