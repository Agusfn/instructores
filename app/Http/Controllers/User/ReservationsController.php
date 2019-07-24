<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Filters\ReservationFilters;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ReservationsController extends Controller
{

	public function __construct()
	{
		$this->middleware("auth:user");
	}


	/**
	 * Show reservation list page.
	 * @return [type]
	 */
	public function showList(ReservationFilters $filters)
	{
		$user = Auth::user();

		$reservations = $user->reservations()->filter($filters)->paginate(10);
		
		return view("user.panel.reservations.list")->with("reservations", $reservations);
	}


	/**
	 * Show reservation details page 
	 * @param  string $code reservation code
	 * @return [type]
	 */
	public function details($code)
	{
		$user = Auth::user();
		$reservation = $user->reservations()->withCode($code)->first();

		if(!$reservation)
			return redirect()->route("user.reservations");

		return view("user.panel.reservations.details")->with([
			"reservation" => $reservation,
			"payment" => $reservation->lastPayment
		]);
	}



}
