<?php

namespace App\Http\Controllers\Api;

use Log;
use App\Lib\MercadoPago;
use App\Lib\Helpers\Dates;
use App\MercadopagoPayment;
use App\ReservationPayment;
use Illuminate\Http\Request;
use App\Lib\ReservationPayments;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class MercadoPagoWebhookController extends Controller
{
    
    private $mpPayment;
    private $mpApiPayment;



    /**
     * Process mercadopago payment updated notification for pending payments (or successful ones) and update entities
     * according to the payment status.
     * 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
	public function updatePayment(Request $request)
	{

		if(($response = $this->validateAndLoad($request)) != null) 
			return $response;

		$mpPayment = $this->mpPayment;
		$mpApiPayment = $this->mpApiPayment;


		if($mpPayment->reservationPayment->isProcessing()) {

			if($mpApiPayment->status == "approved" || $mpApiPayment->status == "rejected") {

				ReservationPayments::updateMpPaymentStatusFromApiPayment($mpPayment, $mpApiPayment);
				$mpPayment->save();

				ReservationPayments::updateReservPaymentStatusFromMpPayment($mpPayment->reservationPayment, $mpPayment);
				$mpPayment->reservationPayment->save();

				$mpPayment->reservationPayment->reservation->updateStatusIfPaid();

				if($mpApiPayment->status == "rejected") {
					// <send mail>
				}

			}
			
		}
		else if($mpPayment->reservationPayment->isSuccessful()) {

			if($mpApiPayment->status == "charged_back") {

				ReservationPayments::updateMpPaymentStatusFromApiPayment($mpPayment, $mpApiPayment);
				$mpPayment->save();

				$mpPayment->reservationPayment->status = ReservationPayment::STATUS_CHARGEBACKED;
				$mpPayment->reservationPayment->save();

				$mpPayment->reservationPayment->reservation->status = Reservation::STATUS_CANCELED;
				$mpPayment->reservationPayment->reservation->save();

				// <mail, events, etc>
			}

		}

		return response("OK", 200);
	}



	/**
	 * Validate and load payment entities.
	 * @return null|
	 */
	private function validateAndLoad($request)
	{
		$validator = Validator::make($request->all(), [
			"action" => "required|in:payment.created,payment.updated",
			"data.id" => "required|integer",
			"type" => "required|in:payment"
		]);

		if(!$request->isJson() || $validator->fails()) {
			return response("Bad request", 400);
		}

		// Ignore created notifications
		if($request->action == "payment.created")
			return response("OK", 200);


		$this->mpPayment = MercadopagoPayment::findByMpId($request->data["id"]);

		if(!$this->mpPayment) {
			Log::notice("MP Webhook: MercadopagoPayment with mp_payment_id ".$request->data["id"]." not found locally.");
			return response("Not found", 404);
		}

		$this->mpApiPayment = MercadoPago::getPayment($request->data["id"]);

		if(!$this->mpApiPayment) {
			Log::notice("MP Webhook: MercadoPago payment entity with id ".$request->data["id"]." could not be found with MP API.");
			return response("Not found", 404);
		}
	}

}
