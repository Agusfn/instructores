<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MercadopagoPayment extends Model
{
    
	protected $guarded = [];

	public $timestamps = false;
	

	/**
	 * Find a MercadopagoPayment by its mercadopago payment id
	 * @param  int $mpId
	 * @return App\MercadopagoPayment|null
	 */
	public static function findByMpId($mpId)
	{
		return self::where("mp_payment_id", $mpId)->first();
	}



	public function reservationPayment()
	{
		return $this->belongsTo("App\ReservationPayment");
	}




}
