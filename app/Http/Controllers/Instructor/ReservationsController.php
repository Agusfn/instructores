<?php

namespace App\Http\Controllers\Instructor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReservationsController extends Controller
{
	

	public function __construct()
	{
		$this->middleware("auth:instructor");
	}

	public function showList()
	{
		return view("instructor.reservations");
	}

}
