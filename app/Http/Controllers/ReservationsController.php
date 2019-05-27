<?php

namespace App\Http\Controllers;

use App\Quote;
use Carbon\Carbon;
use App\InstructorService;
use App\Lib\PaymentMethods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Validators\Reservations\PreviewReservation;


class ReservationsController extends Controller
{


	public function __construct()
	{
		$this->middleware("reservation_steps"); // Instructors get an error, and guests are redirected to user login.
	}




	public function previewReservation(Request $request, $service_number)
	{
		
		$service = InstructorService::findActiveByNumber($service_number);
		if(!$service) {
			return redirect()->route("home");
		}

		$validator = new PreviewReservation($request);
		if($validator->fails()) {
			return redirect()->route("service-page", $service_number)->withErrors($validator->messages());
		}

		
		$quote = new Quote($service);
		$quote->set(
			PaymentMethods::MERCADOPAGO,
			Carbon::createFromFormat("d/m/Y", $request->date),
			$request->persons,
			$request->t_start, $request->t_end
		);
		$quote->calculate();


		$priceChangeWarning = false;

		if($request->has("last_price")) {
			if($request->last_price != $quote->total) {
				$priceChangeWarning = true;
			}
		}

		return view("reservation.preview")->with([
			"service" => $service,
			"quote" => $quote,
			"priceChangeWarning" => $priceChangeWarning
		]);
	}




	public function reservationForm(Request $request, $service_number)
	{
		$service = InstructorService::findActiveByNumber($service_number);
		if(!$service) {
			return redirect()->route("home");
		}

		$validator = new PreviewReservation($request);
		if($validator->fails()) {
			return redirect()->route("service-page", $service_number)->withErrors($validator->messages());
		}


		$quote = new Quote($service);
		$quote->set(
			PaymentMethods::MERCADOPAGO,
			Carbon::createFromFormat("d/m/Y", $request->date),
			$request->persons,
			$request->t_start, $request->t_end
		);
		$quote->calculate();


		return view("reservation.form")->with([
			"service" => $service,
			"quote" => $quote,
			"user" => Auth::user()
		]);


	}


	public function redirectToService($service_number)
	{
		return redirect()->route("service-page", $service_number);
	}




}
