<?php

namespace App\Http\Controllers;


use App\Quote;
use App\Country;
use Carbon\Carbon;
use App\InstructorService;
use App\Lib\PaymentMethods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Validators\Reservations\PreviewReservation;
use App\Http\Validators\Reservations\ProcessReservation;

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



	/**
	 * Shows payment and reservation form, prior to make the reservation.
	 * @param  Request $request        [description]
	 * @param  [type]  $service_number [description]
	 * @return [type]                  [description]
	 */
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
			"user" => Auth::user(),
			"countries" => Country::getNamesAndCodes()
		]);


	}


	/**
	 * Redirects to the service page when the user reloads the reservation form url.
	 * @param  [type] $service_number [description]
	 * @return [type]                 [description]
	 */
	public function redirectToService($service_number)
	{
		return redirect()->route("service-page", $service_number);
	}



	/**
	 * Process the reservation.
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function processReservation(Request $request, $serviceNumber)
	{
		dump($request);

		$service = InstructorService::findActiveByNumber($serviceNumber);
		if(!$service) {
			return redirect()->route("home");
		}

		$validator = new PreviewReservation($request);
		if($validator->fails()) {
			return redirect()->route("service-page", $serviceNumber)->withErrors($validator->messages());
		}

		$validator = new ProcessReservation($request);
		if($validator->fails()) {
			return redirect()->route("service-page", $serviceNumber)->withErrors($validator->messages());
		}

		

		


	}



}
