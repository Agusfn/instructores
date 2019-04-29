<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReservationsController extends Controller
{

	public function __construct()
	{
		$this->middleware("auth:user");
	}

	public function showList()
	{
		return view("user.reservations");
	}

}
