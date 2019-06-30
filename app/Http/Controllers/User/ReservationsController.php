<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
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
	public function showList()
	{
		$user = Auth::user();

		$reservations = $user->reservations()->orderBy("created_at", "DESC")->paginate(10);
		
		return view("user.reservations")->with("reservations", $reservations);
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

		return view("user.reservation")->with([
			"reservation" => $reservation,
			"payment" => $reservation->lastPayment
		]);
	}



}
