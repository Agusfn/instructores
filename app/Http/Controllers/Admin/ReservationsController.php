<?php

namespace App\Http\Controllers\Admin;

use App\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

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
		])->orderBy("created_at", "DESC")
		->paginate(15);

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



	/**
	 * Cancel a pending and not concluded reservation by admin, refunding payment if paid.
	 * @param  Request $request [description]
	 * @param  [type]  $id      [description]
	 * @return [type]           [description]
	 */
	public function cancel(Request $request, $id)
	{
		$reservation = Reservation::find($id);

		if(!$reservation)
			return redirect()->route("admin.reservations.list");

		if(!$reservation->isPaymentPending() && !$reservation->isPendingConfirmation() && !$reservation->isConfirmed())
			return redirect()->back();


		if($reservation->isPaymentPending() && $reservation->lastPayment->isProcessing())
			return redirect()->back()->withErrors("El pago de esta reserva se está procesando. Espera a que se termine de procesar para cancelarla.");


		// Refund if paid, cancel if pending/processing.
		if($reservation->isPendingConfirmation() || $reservation->isConfirmed()) {
			if(!$reservation->lastPayment->refund()) {
				return redirect()->back()->withErrors("Ha ocurrido un error intentando reembolsar el pago, contacta a soporte.");
			}
		}
		else if($reservation->isPaymentPending() && ($reservation->lastPayment->isPending() || $reservation->lastPayment->isProcessing())) {

			if(!$reservation->lastPayment->cancel()) {
				return redirect()->back()->withErrors("Ha ocurrido un error intentando cancelar el pago, contacta a soporte.");
			}

		}

		$reservation->cancel();

		Mail::to($reservation->instructor)->send(new App\Mail\Instructor\Reservations\ReservationCanceledByAdmin($reservation->instructor, $reservation, $request->cancel_reason));
		Mail::to($reservation->user)->send(new App\Mail\User\Reservations\ReservationCanceledByAdmin($reservation->user, $reservation, $request->cancel_reason));

		return redirect()->back();
	}



}
