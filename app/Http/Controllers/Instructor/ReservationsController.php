<?php

namespace App\Http\Controllers\Instructor;

use App\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;


class ReservationsController extends Controller
{
	

	public function __construct()
	{
		$this->middleware("auth:instructor");
	}


	public function showList()
	{
		$instructor = Auth::user();

		$reservations = $instructor->service->reservations;
		return view("instructor.reservations")->with("reservations", $reservations);
	}


	public function details($code)
	{
		$reservation = Reservation::findByCode($code);

		if(!$reservation)
			return redirect('instructor/panel/reservas');

		return view("instructor.reservation");
	}




}
