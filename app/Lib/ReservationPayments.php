<?php

namespace App\Lib;


use MercadoPago;
use App\ReservationPayment;

class ReservationPayments
{


	public static function makeMercadoPagoPayment($reservation, $quote, ...$params)
	{
		//MercadoPago::pago($params)
		
		$payment_result = "success";



		if($payment_result == "success") {
			$paymentStatus = ReservationPayment::STATUS_SUCCESSFUL;
		}
		else if($payment_result == "pending") {
			$paymentStatus = ReservationPayment::STATUS_PROCESSING;
		}
		else if($payment_result == "rejected") {
			$paymentStatus = ReservationPayment::STATUS_FAILED;
		}	

		$payment = ReservationPayment::create([
			"reservation_id" => $reservation->id,
			"status" => $paymentStatus,
			"payment_method_code" => $quote->paymentMethod,
			"mercadopago_payment_id" => 0,
			"total_amount" => $quote->total,
			"currency_code" => $quote->currency
		]);

		return $payment;
	}



}