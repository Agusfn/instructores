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

use App\Lib\AdminEmailNotifications;
use Illuminate\Support\Facades\Mail;
use App\Mail\Admin\Payments\PaymentChargebacked;
use App\Mail\User\Payments\ProcessingPaymentFailed;
use App\Mail\Instructor\Reservations\ReservationCanceledByChargeback as MailReservChargebackedInstructor;
use App\Mail\User\Reservations\ReservationCanceledByChargeback as MailReservChargebackedUser;

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

		$reservPayment = $mpPayment->reservationPayment;


		if($reservPayment->isPending() || $reservPayment->isProcessing() || $reservPayment->isExpired()) { // ticket or atm will be pending or expired, credit card will be processing.

			if($mpApiPayment->status == "approved" || $mpApiPayment->status == "rejected") {

				ReservationPayments::updateMpPaymentStatusFromApiPayment($mpPayment, $mpApiPayment);
				$mpPayment->save();

				ReservationPayments::updateReservPaymentStatusFromMpPayment($reservPayment, $mpPayment);
				$reservPayment->save();

				if($reservPayment->reservation->isPaymentPending()) {
					$reservPayment->reservation->updateStatusIfPaid();
				}

				if($mpApiPayment->status == "rejected") { // only credit card
					Mail::to($reservPayment->reservation->user)->send(new ProcessingPaymentFailed($reservPayment->reservation->user, $reservPayment, $reservPayment->reservation));
				}

			}
			
		}
		else if($reservPayment->isSuccessful()) {

			if($mpApiPayment->status == "charged_back") { // credit card (and ticket?)

				ReservationPayments::updateMpPaymentStatusFromApiPayment($mpPayment, $mpApiPayment);
				$mpPayment->save();

				$reservPayment->status = ReservationPayment::STATUS_CHARGEBACKED;
				$reservPayment->save();

				if(!$reservPayment->reservation->isConcluded()) {

					$reservPayment->reservation->cancel();

					Mail::to($reservPayment->reservation->instructor)->send(new MailReservChargebackedInstructor($reservPayment->reservation->instructor, $reservPayment->reservation));
					Mail::to($reservPayment->reservation->user)->send(new MailReservChargebackedUser($reservPayment->reservation->user, $reservPayment->reservation));
				}


				Mail::to(AdminEmailNotifications::recipients())->send(new PaymentChargebacked($reservPayment, $reservPayment->reservation));
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
