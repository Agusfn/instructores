<?php

namespace App\Http\Controllers\Admin;

use App\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReservationsController extends Controller
{
  	public function __construct()
	{
		$this->middleware('auth:admin');
	}


	/**
	 * Display reservation list. *** DO PAGINATION AND FILTERS ***
	 * @return [type] [description]
	 */
	public function list()
	{
		$reservations = Reservation::with([
			"user:id,name,surname",
			"instructor:id,name,surname"
		])->get();

		return view("admin.reservations.list")->with("reservations", $reservations);
	}


	/**
	 * Show details page of reservation.
	 * @return [type] [description]
	 */
	public function details($id)
	{
		$reservation = Reservation::find($id);

		if(!$reservation)
			return redirect()->route("admin.reservations.list");

		return view("admin.reservations.details")->with("reservation", $reservation);
	}


}
