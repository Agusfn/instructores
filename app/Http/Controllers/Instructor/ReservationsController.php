<?php

namespace App\Http\Controllers\Instructor;

use App\Reservation;
use App\Lib\MercadoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\User\Reservations\ReservationConfirmedByInstructor;
use App\Mail\User\Reservations\ReservationRejectedByInstructor;

class ReservationsController extends Controller
{
	

	public function __construct()
	{
		$this->middleware("auth:instructor")->only("index");
		$this->middleware("instructor.approved")->except("index");
	}


	/**
	 * Show reservation list page.
	 * @return [type]
	 */
	public function showList()
	{
		$instructor = Auth::user();

		if($instructor->isApproved()) {
			$reservations = $instructor->reservations()->orderBy("created_at", "DESC")->paginate(10);
		}
		else
			$reservations = null;
		
		return view("instructor.reservations.list")->with([
			"instructor" => $instructor,
			"reservations" => $reservations
		]);
	}


	/**
	 * Show reservation details page 
	 * @param  string $code reservation code
	 * @return [type]
	 */
	public function details($code)
	{
		$instructor = Auth::user();
		$reservation = $instructor->reservations()->withCode($code)->first();

		if(!$reservation)
			return redirect()->route("instructor.reservations");

		return view("instructor.reservations.details")->with([
			"reservation" => $reservation,
			"payment" => $reservation->lastPayment
		]);
	}



	/**
	 * Confirm a paid reservation that is pending confirmation and send an e-mail to the client (user)
	 * @param  Request $request          [description]
	 * @param  [type]  $reservation_code [description]
	 * @return [type]                    [description]
	 */
	public function confirm(Request $request, $reservation_code)
	{
		$request->validate([
			"confirm_message" => "nullable|string"
		]);

		$instructor = Auth::user();

		$reservation = $instructor->reservations()->withCode($reservation_code)->first();

		if(!$reservation)
			return redirect()->route("instructor.reservations");


		// confirm
		$reservation->fill([
			"status" => Reservation::STATUS_CONFIRMED,
			"confirm_message" => $request->confirm_message
		]);
		$reservation->save();


		Mail::to($reservation->user)->send(new ReservationConfirmedByInstructor($reservation->user, $reservation));


		return redirect()->route("instructor.reservation", $reservation_code);
	}



	/**
	 * Confirm a paid reservation that is pending confirmation, refund the payment, and send an e-mail to the client (user)
	 * @param  Request $request          [description]
	 * @param  [type]  $reservation_code [description]
	 * @return [type]                    [description]
	 */
	public function reject(Request $request, $reservation_code)
	{
		$request->validate([
			"reject_reason" => "nullable|string"
		]);

		$instructor = Auth::user();

		$reservation = $instructor->reservations()->withCode($reservation_code)->first();

		if(!$reservation || !$reservation->isPendingConfirmation())
			return redirect()->route("instructor.reservations");


		if(!$reservation->lastPayment->refund())
			return redirect()->back()->withErrors("Ha ocurrido un error intentando reembolsar el pago, contacta a soporte.");

		$reservation->reject($request->reject_reason);


		Mail::to($reservation->user)->send(new ReservationRejectedByInstructor($reservation->user, $reservation, $request->reject_reason));
		

		return redirect()->route("instructor.reservation", $reservation_code);
	}



}
