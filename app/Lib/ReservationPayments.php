<?php

namespace App\Lib;


use Log;
use \App\Lib\PaymentMethods;
use \App\Lib\MercadoPago;
use App\Lib\Helpers\Dates;
use App\InstructorService;
use App\MercadopagoPayment;
use App\ReservationPayment;


class ReservationPayments
{


	/**
	 * Process a MercadoPago API payment.
	 * @param  string 			$cardToken    [description]
	 * @param  int 				$issuerId     [description]
	 * @param  string 			$payMethodId  [description]
	 * @param  int 				$installments [description]
	 * @param  App\User 		$user         [description]
	 * @param  App\Reservation 	$reservation  [description]
	 * @return App\ReservationPayment
	 */
	public static function processMpApiPayment($cardToken, $issuerId, $payMethodId, $installments, $user, $reservation)
	{
	    
	    $description = self::paymentDescription($reservation);

	    $mpApiPayment = MercadoPago::createPayment(
	    	$cardToken, 
	    	$issuerId, 
	    	$payMethodId, 
	    	$installments, 
	    	$reservation->final_price, 
	    	$description, 
	    	MercadoPago::payerArray($user->email, $user->name, $user->surname), 
	    	$reservation->code
	    );

	    
	    if($mpApiPayment->status != null) {
	    	$mpPayment = self::createMpPaymentFromApiPayment($mpApiPayment);
	    }
	    else {
	    	$errorText = $mpApiPayment->error->message." (code ".$mpApiPayment->error->causes[0]->code.")";
	    	
	    	$mpPayment = MercadopagoPayment::create([
	    		"status" => "error",
	    		"error_msg" => $errorText
		    ]);

	    	Log::notice("Error processing MercadoPago API Payment for reservation ID ".$reservation->id.", total: $".$reservation->final_price." ARS, payer email: ".$user->email.". Error obtained: ".$errorText);
	    }


	    $reservationPayment = self::createReservationPaymentFromMpPayment($reservation, $mpPayment);

		$mpPayment->reservation_payment_id = $reservationPayment->id;
		$mpPayment->save();

		return $reservationPayment;
	}



	/**
	 * Create a MercadopagoPayment entity out of a SUCCESSFUL response (payment may fail or not) of mercadopago api
	 * @param  \MercadoPago\Payment 	$mpApiPayment 	mercadopago payment api response
	 * @return App\MercadopagoPayment
	 */
	private static function createMpPaymentFromApiPayment($mpApiPayment)
	{
	    $mpPayment = new MercadopagoPayment();

	    $mpPayment->fill([
	    	"status" => $mpApiPayment->status,
			"status_detail" => $mpApiPayment->status_detail,
			"date_created" => Dates::iso8601ToMysql($mpApiPayment->date_created),
			"date_updated" => Dates::iso8601ToMysql($mpApiPayment->date_last_updated),
			"payment_method_id" => $mpApiPayment->payment_method_id,
			"payment_type_id" => $mpApiPayment->payment_type_id,
			"installment_amount" => $mpApiPayment->installments,
			"total_amount" => $mpApiPayment->transaction_details->total_paid_amount,
			"last_four_digits" => $mpApiPayment->card->last_four_digits,
			"issuer_id" => $mpApiPayment->issuer_id,
			"cardholder_name" => $mpApiPayment->card->cardholder->name,
			"cardholder_id_type" => $mpApiPayment->card->cardholder->identification->type,
			"cardholder_id" => $mpApiPayment->card->cardholder->identification->number
	    ]);

	    if($mpApiPayment->status == "approved") {
	    	$mpPayment->fill([
	    		"date_approved" => Dates::iso8601ToMysql($mpApiPayment->date_approved),
	    		"collector_fee" => $mpApiPayment->installments == 1 ? $mpApiPayment->fee_details[0]->amount : $mpApiPayment->fee_details[1]->amount,
	    		"financing_costs" => $mpApiPayment->installments > 1 ? $mpApiPayment->fee_details[0]->amount : 0,
	    		"net_received" => $mpApiPayment->transaction_details->net_received_amount,
	    	]);
	    }

	    $mpPayment->save();
	    return $mpPayment;
	}



	/**
	 * Creates a ReservationPayment (which generalizes any kind of payment) related to a MercadopagoPayment that was just created.
	 * 
	 * @param  App\Reservation 			$reservation 	Reservation to base the new ReservationPayment
	 * @param  App\MercadopagoPayment 	$mpPayment 		Mercadopago payment recently created and processed (successful or not)
	 * @return App\ReservationPayment
	 */
	private static function createReservationPaymentFromMpPayment($reservation, $mpPayment)
	{
		$reservationPayment = new ReservationPayment();

		$reservationPayment->fill([
			"reservation_id" => $reservation->id,
			"status" => self::mpStatusToPaymentStatus($mpPayment->status),
			"payment_method_code" => PaymentMethods::CODE_MERCADOPAGO,
			"mercadopago_payment_id" => $mpPayment->id,
			"total_amount" => $mpPayment->total_amount ?: $reservation->final_price, // $mpPayment->total_amount = null if status = 'error'
			"currency_code" => "ARS"
		]);

		if($mpPayment->status == "approved") {
			$reservationPayment->fill([
				"paid_at" => $mpPayment->date_approved,
				"payment_provider_fee" => $mpPayment->collector_fee,
				"financing_costs" => $mpPayment->financing_costs,
				"net_received" => $mpPayment->net_received
			]);
		}

		$reservationPayment->save();
		return $reservationPayment;
	}


	/**
	 * Make a short text description to provide to the payment processor.
	 * @param  App\Reservation $reservation
	 * @return string
	 */
	private static function paymentDescription($reservation) 
	{
		$description = "Clases de ".ucfirst($reservation->sport_discipline)." para ".$reservation->personAmount()." personas.";
		return $description;
	}



	/**
	 * Get the ReservationPayment respective status from the payment status string responded from MP API.
	 * @param  string $mpStatus
	 * @return string
	 */
	private static function mpStatusToPaymentStatus($mpStatus)
	{
		if($mpStatus == "approved") {
			return ReservationPayment::STATUS_SUCCESSFUL;
		}
		else if($mpStatus == "in_process") {
			return ReservationPayment::STATUS_PROCESSING;
		}
		else { // "rejected" or "error"
			return ReservationPayment::STATUS_FAILED;
		}	
	}



}