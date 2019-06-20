<?php

namespace App\Lib;

use Log;
use MercadoPago\SDK;

class MercadoPago
{

	/*const APP_ID = "6705395561099232";
	const CLIENT_SECRET = "ThsvOBRnc5JHdx4ONVTV3UteQlKpkZ5c";*/

	//const REDIRECT_URI = "https://instructores.com.ar/";

	/*const PUBLIC_KEY_SANDBOX = "TEST-4dc699b4-f988-46cf-945d-48c4ec565ef9";
	const ACCESS_TOKEN_SANDBOX = "TEST-6705395561099232-052309-1580f7548ef0b8472cd109ec6e86c361-92382327";

	// These production keys must be enabled first
	const PUBLIC_KEY_PRODUCTION = "APP_USR-0117a576-7259-494a-84dd-4e66bcfda82d";
	const ACCESS_TOKEN_PRODUCTION = "APP_USR-6705395561099232-052309-438a3e46ae113c86f51ef3313de0ece3-92382327";*/


	/**
	 * The text that will be displayed on the card statement.
	 */
	const STATEMENT_DESCRIPTOR = "INSTRUCTORES";



	private static $initialized = false;



	/**
	 * Initializes static class if not initialized, setting MercadoPago SDK credentials.
	 * @return null
	 */
	private static function initialize()
	{
		if(!self::$initialized) {
			SDK::setAccessToken(config("services.mercadopago.access_token"));
			self::$initialized = true;
		}

	}


	public static function createPayment($cardToken, $issuerId, $payMethodId, $installments, $total, $description, $payer, $extReference)
	{
		self::initialize();

	    $payment = new \MercadoPago\Payment();
	    $payment->transaction_amount = $total;
	    $payment->token = $cardToken;
	    $payment->description = $description;
	    $payment->statement_descriptor = self::STATEMENT_DESCRIPTOR;
	    $payment->installments = $installments;
	    $payment->external_reference = $extReference;
	    $payment->payment_method_id = $payMethodId;
	    $payment->notification_url = config("services.mercadopago.webhook_url");

	    if($issuerId) {
	    	$payment->issuer_id = $issuerId;
	    }
	    
	    $payment->payer = $payer;

	    $payment->save();

	    return $payment;
	}


	/**
	 * Obtain a MP Payment from their API.
	 * @param  int $paymentId 	id of the mercadopago's payment entity.
	 * @return \MercadoPago\Payment
	 */
	public static function getPayment($paymentId)
	{
		self::initialize();

		return \MercadoPago\Payment::find_by_id($paymentId);
	}



	/**
	 * Refund a payment totally. Must be done to approved payments within 360 days.
	 * @param  int $paymentId 	id of the mercadopago's payment entity.
	 * @return boolean
	 */
	public static function refundPayment($paymentId)
	{
		self::initialize();

		$refund = new \MercadoPago\Refund();
		$refund->payment_id = $paymentId;
		
		if($refund->save()) {
			return true;
		}
		else {
			Log::notice("Refund to MP Payment id ".$paymentId." could not be done. Message: ".$refund->error->message." (code ".$refund->error->causes[0]->code.")");
			return false;
		}

	}



	/**
	 * Simple helper to make the payer array that MP API expects.
	 */
	public static function payerArray($email, $name, $surname)
	{
		return array(
	    	"first_name" => $name,
	    	"last_name" => $surname,
	    	"email" => $email
	    );
	}





}