<?php

namespace App\Lib;


use MercadoPago\SDK;
use MercadoPago\Payment;

class MercadoPago
{

	const APP_ID = "6705395561099232";
	const CLIENT_SECRET = "ThsvOBRnc5JHdx4ONVTV3UteQlKpkZ5c";

	const REDIRECT_URI = "https://instructores.com.ar/";

	const PUBLIC_KEY_SANDBOX = "TEST-4dc699b4-f988-46cf-945d-48c4ec565ef9";
	const ACCESS_TOKEN_SANDBOX = "TEST-6705395561099232-052309-1580f7548ef0b8472cd109ec6e86c361-92382327";

	// These production keys must be enabled first
	const PUBLIC_KEY_PRODUCTION = "TEST-4dc699b4-f988-46cf-945d-48c4ec565ef9";
	const ACCESS_TOKEN_PRODUCTION = "TEST-6705395561099232-052309-1580f7548ef0b8472cd109ec6e86c361-92382327";


	private static $initialized = false;



	/**
	 * Initializes static class if not initialized, setting MercadoPago SDK credentials.
	 * @return null
	 */
	private static function initialize()
	{
		if(!self::$initialized) {

			SDK::setAccessToken(self::getAccessToken());
			self::$initialized = true;

		}

	}

	/**
	 * Obtains API public key.
	 * @return string
	 */
	public static function getPublicKey() 
	{
		if(config("app.env") == "production")
			return self::PUBLIC_KEY_PRODUCTION;
		else
			return self::PUBLIC_KEY_SANDBOX;
	}

	/**
	 * Obtains API access token.
	 * @return string
	 */
	public static function getAccessToken() 
	{
		if(config("app.env") == "production")
			return self::ACCESS_TOKEN_PRODUCTION;
		else
			return self::ACCESS_TOKEN_SANDBOX;
	}


	/**
	 * Obtains the URL in which instructors MercadoPago accounts can be associated with our MercadoPago account (Marketplace feature).
	 * Also includes redirect url to finish association.
	 * More info: mercadopago.com.ar/developers/es/guides/marketplace/api/create-marketplace/
	 * @param  string $redirectUrl
	 * @return string
	 */
	public static function marketplaceAssociationUrl($redirUrlParams)
	{
		// redirect uri must be within https://instructores.com.ar. So to test this, after being redirected to fake url in production site,
		// the first part of the url should be replaced with the test/local hostname.
		if(request()->getHost() == "localhost") 
			$redirectUrl = url("https://instructores.com.ar/test/instructor/panel/cobros/asociar_cuenta_mp".$redirUrlParams); 
		else
			$redirectUrl = url("https://instructores.com.ar/instructor/panel/cobros/asociar_cuenta_mp".$redirUrlParams); 
		

		return "https://auth.mercadopago.com.ar/authorization?client_id=".self::APP_ID."&response_type=code&platform_id=mp&redirect_uri=".urlencode($redirectUrl);
	}



	/**
	 * Finish the association process of a 'seller' MercadoPago account with our 'marketplace' mercadopago account.
	 * This is done with the authorization code obtained on redirect after authorizing the association on mercadopago.com login screen.
	 * $urlParams must be the same that were provided on marketplaceAssociationUrl()
	 * 
	 * @param string 	$authCode
	 * @param string 	$urlParams
	 * @return array|false
	 */
	public static function processMarketplaceAssociation($authCode, $urlParams)
	{
		self::initialize();

		if(request()->getHost() == "localhost") 
			$redirectUrl = url("https://instructores.com.ar/test/instructor/panel/cobros/asociar_cuenta_mp".$urlParams); 
		else
			$redirectUrl = url("https://instructores.com.ar/instructor/panel/cobros/asociar_cuenta_mp".$urlParams); 


		$payload = [
			"json_data" => [
				"client_id" => self::APP_ID,
				"client_secret" => self::CLIENT_SECRET,
				"grant_type" => "authorization_code",
				"code" => $authCode,
				"redirect_uri" => $redirectUrl
			]
		];

		$response = SDK::post("/oauth/token", $payload);
		dump($response);

		if($response["code"] == 200) {
			return $response;
		}
		else {
			\Log::warning("Error associating MercadoPago seller account to marketplace account. Code: ".$response["code"].". Message: ".$response["body"]["message"]);
			return false;
		}

	}




	public static function makeMarketplacePayment($itemName, $externalRef, $total, $payer, $cardToken, $payMethodId, $installments, $issuerId = null)
	{
		self::initialize();

		$payment = new MercadoPago\Payment();
	    $payment->transaction_amount = 117;
	    $payment->token = "ff8080814c11e237014c1ff593b57b4d";
	    $payment->application_fee = 123;
	    $payment->issuer_id = 123;
	    $payment->description = "Durable Steel Gloves";
	    $payment->installments = 1;
	    $payment->statement_descriptor = "INSTRUCTORES.COM.AR";
	    $payment->payment_method_id = "visa";
	    $payment->payer = array(
	    	"email" => "michele_barrows@gmail.com"
	    );
	    // Save and posting the payment
	    $payment->save();
	    //...
	    // Print the payment status
	    echo $payment->status;

	}




	public static function testAdvPayment()
	{
		self::initialize();

		$data = [

			"application_id" => self::APP_ID,
			"payments" => [
				[
					"payment_method_id" => "",
					"payment_type_id" => "",
					"token" => "",
					"transaction_amount" => 100,
					"installments" => 1,
					"processing_mode" => "aggregator",
					"description" => "Item description",
					"statement_descriptor" => "INSTRUCTORES.COM.AR",
					"external_reference" => "EXTREF1"
				]
			],
			"disbursements" => [
				[
					"amount" => 100,
					"external_reference" => "EXTREF2",
					"collector_id" => 1234444, // ID del vendedor
					"application_fee" => 5.5,
					//"money_release_days" => 3,
				]
			],
			"payer" => [
				"email" => "agusfn20@gmail.com"
			],


		];


		$payload = [
			"json_data" => $data
		];
		$response = SDK::post("/v1/advanced_payments?access_token=".self::getAccessToken(), $payload);

		dump($response);
	}

}