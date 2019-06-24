<?php

namespace App\Http\Controllers;


use App\Quote;
use App\Country;
use App\Reservation;
use Carbon\Carbon;
use App\Lib\Reservations;
use App\InstructorService;
use App\ReservationPayment;
use App\Lib\PaymentMethods;
use Illuminate\Http\Request;
use App\Lib\ReservationPayments;
use Illuminate\Support\Facades\Auth;
use App\Http\Validators\Reservations\ProcessReservation;
use App\Http\Validators\Reservations\ReservationAvailable;


class ReservationsController extends Controller
{



	/**
	 * The service to make a reservation for.
	 * @var App\InstructorService
	 */
	private $service;

	/**
	 * Quote of the classes to be reserved.
	 * @var App\Quote
	 */
	private $quote;



	public function __construct()
	{
		$this->middleware("reservation_steps"); // Instructors get an error, and guests are redirected to user login (***NEEDS REDIRECTION AFTER LOGIN***).
	}



	/**
	 * Step 1: Show reservation details preview.
	 * @param  Request $request        
	 * @param  int  $service_number 
	 * @return [type]                  
	 */
	public function previewReservation(Request $request, $service_number)
	{

		$redirect = $this->validateAndQuoteReservation($request, $service_number);
		if($redirect)
			return $redirect;

		$priceChangeWarning = false;

		if($request->has("last_price")) {
			if($request->last_price != $this->quote->total) {
				$priceChangeWarning = true;
			}
		}

		return view("reservation.preview")->with([
			"service" => $this->service,
			"quote" => $this->quote,
			"priceChangeWarning" => $priceChangeWarning
		]);
	}


	/**
	 * Step 2: Show payment and reservation form, prior to processing the reservation.
	 * @param  Request $request        [description]
	 * @param  [type]  $service_number [description]
	 * @return [type]                  [description]
	 */
	public function reservationForm(Request $request, $service_number)
	{

		$redirect = $this->validateAndQuoteReservation($request, $service_number);
		if($redirect)
			return $redirect;

		return view("reservation.form")->with([
			"service" => $this->service,
			"quote" => $this->quote,
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
	 * ****** CHECK TO AVOID RE-SENDING OF THIS FORM!! **
	 * Step 3: Process the reservation.
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function processReservation(Request $request, $service_number)
	{

		$redirect = $this->validateAndQuoteReservation($request, $service_number);
		if($redirect)
			return $redirect;


		$validator = new ProcessReservation($request);
		if($validator->fails()) {
			return redirect()->route("service-page", $service_number)->withErrors($validator->messages());
		}


		if($request->total_amount != $this->quote->total) {
			return redirect()->route("service-page", $service_number)->withErrors("El precio la reserva que se intentó realizar cambió antes de confirmarla.");
		}


		$user = Auth::user();

		if($user->phone_number == null) {
			$user->phone_number = $request->phone;
			$user->save();
		}


		$hourRange = Reservations::blockRangeToHourRange($request->t_start, $request->t_end);

		$reservation = Reservation::create([
			"code" => Reservation::generateCode(),
			"user_id" => $user->id,
			"instructor_id" => $this->service->instructor_id,
			"instructor_service_id" => $this->service->id,
			"status" => Reservation::STATUS_PAYMENT_PENDING,
			"sport_discipline" => $request->discipline,
			"reserved_class_date" => $this->quote->serviceDate->format("Y-m-d"),
			"reserved_time_start" => $hourRange[0],
			"reserved_time_end" => $hourRange[1],
			"time_blocks_amount" => ($request->t_end - $request->t_start + 1),
			"price_per_block" => $this->quote->pricePerBlock,
			"adults_amount" => $request->adults_amount,
			"kids_amount" => $request->kids_amount,
			"json_breakdown" => $this->quote->getJsonBreakdown(),
			"final_price" => $this->quote->total, // changes if paid in installments
			"instructor_pay" => $this->quote->instructorPay,
			"service_fee" => $this->quote->serviceFee,
			"payment_proc_fee" => $this->quote->payProviderFee, // guessed. Any later change affect service_fee
			"billing_address" => $request->address,
			"billing_city" => $request->address_city,
			"billing_state" => $request->address_state,
			"billing_postal_code" => $request->address_postal_code,
			"billing_country_code" => $request->address_country,
		]);


		$reservPayment = ReservationPayments::makeMpApiPayment(
			$user,
			$reservation,
			$request->payment_type,
			$request->paymentMethodId,
			$request->card_token,
			$request->issuer,
			$request->installments
		);



		$reservation->updateStatusIfPaid();


		// <Update instructor calendar availability>
		// <And update instructor daily availability index for search>

		return redirect()->route("reservation.result", $reservation->code);
	}



	/**
	 * Step 4: Display reservation payment result.
	 * @param  Request $request [description]
	 * @param string $reservationCode
	 * @return [type]           [description]
	 */
	public function showResult(Request $request, $reservationCode)
	{
		$user = Auth::user();
		$reservation = $user->reservations()->withCode($reservationCode)->first();

		if(!$reservation) {
			return redirect()->route("home");
		}

		if($reservation->isPaymentPending() || $reservation->isPendingConfirmation()) {
			return view("reservation.result")->with([
				"reservation" => $reservation,
				"lastPayment" => $reservation->lastPayment
			]);
		}
		else 
			return redirect()->route("user.reservation", $reservationCode);

	}



	/**
	 * [retryPaymentForm description]
	 * @param  Request $request         [description]
	 * @param  [type]  $reservationCode [description]
	 * @return [type]                   [description]
	 */
	public function retryPaymentForm(Request $request, $reservationCode)
	{
		$user = Auth::user();
		$reservation = $user->reservations()->withCode($reservationCode)->first();

		if(!$reservation)
			return redirect()->route("home");

		if(!$reservation->isPaymentPending() || !$reservation->lastPayment->isFailed())
			return redirect()->route("user.reservation", $reservationCode);
		

		return view("reservation.retry-mp-payment")->with([
			"user" => $user,
			"reservation" => $reservation,
			"countries" => Country::getNamesAndCodes()
		]);
	}



	/**
	 * Retry payment of an unpaid reservation through MercadoPago.
	 * @return [type] [description]
	 */
	public function retryMpPayment(Request $request, $reservationCode)
	{

		$validator = new ProcessReservation($request);
		if($validator->fails()) {
			return redirect()->route("service-page", $service_number)->withErrors($validator->messages());
		}

		$user = Auth::user();
		$reservation = $user->reservations()->withCode($reservationCode)->first();

		if(!$reservation)
			return redirect()->route("home");

		if(!$reservation->isPaymentPending() || !$reservation->lastPayment->isFailed())
			return redirect()->route("user.reservation", $reservationCode);


		$reservation->fill([
			"billing_address" => $request->address,
			"billing_city" => $request->address_city,
			"billing_state" => $request->address_state,
			"billing_postal_code" => $request->address_postal_code,
			"billing_country_code" => $request->address_country,
		]);
		$reservation->save();

		$reservPayment = ReservationPayments::processMpApiPayment(
			$request->card_token,
			$request->issuer,
			$request->paymentMethodId,
			$request->installments,
			$user,
			$reservation
		);

		$reservation->updateStatusIfPaid();

		return redirect()->route("reservation.result", $reservation->code);
	}



	/**
	 * Fetch the InstructorService, validate the data to make the reservation, checks availability, and generate a quote.
	 * @param  [type] $request
	 * @param int $serviceNumber
	 * @return [type]
	 */
	private function validateAndQuoteReservation($request, $serviceNumber)
	{
		$this->service = InstructorService::findActiveByNumber($serviceNumber);
		if(!$this->service) {
			return redirect()->route("home");
		}

		$validator = new ReservationAvailable($request);
		if($validator->fails()) {
			return redirect()->route("service-page", $serviceNumber)->withErrors($validator->messages());
		}

		$this->quote = new Quote($this->service);
		$this->quote->set(
			$request->discipline,
			PaymentMethods::CODE_MERCADOPAGO,
			Carbon::createFromFormat("d/m/Y", $request->date),
			$request->adults_amount ?? 0, $request->kids_amount ?? 0,
			$request->t_start, $request->t_end
		);
		$this->quote->calculate();
	}


}
