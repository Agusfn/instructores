<?php

namespace App\Http\Controllers\User;

use App\Claim;
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
		
		return view("user.reservations.list")->with("reservations", $reservations);
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

		return view("user.reservations.details")->with([
			"reservation" => $reservation,
			"payment" => $reservation->lastPayment
		]);
	}




	/**
	 * Display the make claim form for a certain reservation.
	 * @return [type] [description]
	 */
	public function showClaimForm($reservationCode)
	{
		$user = Auth::user();
		$reservation = $user->reservations()->withCode($reservationCode)->first();

		if(!$reservation)
			return redirect()->route("user.reservations");


		if($reservation->claim()->exists()) {
			return redirect()->route("user.reservations.claim", $reservation->code);
		}
		if(!$reservation->isPaymentPending() && !$reservation->isPendingConfirmation() && !$reservation->isConfirmed()) {
			return redirect()->route("user.reservation", $reservation->code);
		}
		

		return view("user.reservations.claim-form")->with("reservation", $reservation);


	}


	/**
	 * Submit a claim.
	 * @return [type] [description]
	 */
	public function submitClaim(Request $request, $reservationCode)
	{

		$user = Auth::user();
		$reservation = $user->reservations()->withCode($reservationCode)->first();

		if(!$reservation)
			return redirect()->route("user.reservations");

		$request->validate([
			"motive" => "required",
			"description" => "required"
		]);

		Claim::create([
			"number" => Claim::generateNumber(),
			"status" => Claim::STATUS_OPEN,
			"user_id" => $user->id,
			"reservation_id" => $reservation->id,
			"motive" => $request->motive,
			"description" => $request->description
		]);

		return redirect()->route("user.reservations.claim", $reservation->code);
	}



	/**
	 * Display claim details.
	 * @param  [type] $reservationCode [description]
	 * @return [type]                  [description]
	 */
	public function showClaimDetails($reservationCode)
	{
		
		$user = Auth::user();
		$reservation = $user->reservations()->withCode($reservationCode)->first();

		if(!$reservation)
			return redirect()->route("user.reservations");

		if(!$reservation->claim)
			return redirect()->route("user.reservation", $reservation->code);
		

		$reservation->claim->load("messages");


		return view("user.reservations.claim-details")->with([
			"reservation" => $reservation,
			"claim" => $reservation->claim,
			"messages" => $reservation->claim->messages
		]);

	}


	/**
	 * Submit a new message as current user for the claim of this reservation.
	 * @param  Request $request         [description]
	 * @param  [type]  $reservationCode [description]
	 * @return [type]                   [description]
	 */
	public function submitClaimMessage(Request $request, $reservationCode)
	{
		$user = Auth::user();
		$reservation = $user->reservations()->withCode($reservationCode)->first();

		if(!$reservation)
			return redirect()->route("user.reservations");

		if(!$reservation->claim)
			return redirect()->route("user.reservation", $reservation->code);

		if($reservation->claim->isClosed())
			return redirect()->route("user.reservations.claim", $reservation->code);


		$request->validate(["message" => "required"]);


		$reservation->claim->addMessage($user, $request->message);

		return redirect()->back();
	}


}
