<?php
namespace App\Lib;



class PaymentMethods
{


	const CODE_MERCADOPAGO = "mercadopago-ar";


	const MP_FEE_PERCENTAGE = 0.066429; // 5.49% + iva 21%


	/**
	 * Assign one currency per payment method.
	 * @var array
	 */
	public static $currencies = [
		self::CODE_MERCADOPAGO => Currencies::CODE_ARS
	];



	/**
	 * Calculates the necessary fee that should be added to the provided ammount, to recieve in net that same provided ammount.
	 * @param  float $to_recieve 
	 * @return float
	 */
	public static function calculateMercadoPagoFees($to_recieve)
	{
		return round($to_recieve * (1/(1 - self::MP_FEE_PERCENTAGE) - 1), 2);
	}


	//public static function btcMethodEnabled() { }
	//public static function setBtcMethodEnabled() {}


}