@extends('layouts.main')

@section('title', 'Resultado reserva')


@section('custom-css')
<style type="text/css">
	.hero_in .wrapper p a {
		color: #FFF;
		text-decoration: underline;
	}
</style>
@endsection

@section('content')
		<div class="hero_in cart_section last">
			<div class="wrapper">
				<div class="container">
					<div class="bs-wizard clearfix">
						<div class="bs-wizard-step">
							<div class="text-center bs-wizard-stepnum">Tu reserva</div>
							<div class="progress">
								<div class="progress-bar"></div>
							</div>
							<a href="cart-1.html" class="bs-wizard-dot"></a>
						</div>

						<div class="bs-wizard-step @if($lastPayment->isFailed()) disabled @endif">
							<div class="text-center bs-wizard-stepnum">Pago</div>
							<div class="progress">
								<div class="progress-bar"></div>
							</div>
							<a href="cart-2.html" class="bs-wizard-dot"></a>
						</div>

						<div class="bs-wizard-step active @if(!$reservation->isPendingConfirmation() && !$reservation->isConfirmed()) disabled @endif">
							<div class="text-center bs-wizard-stepnum">Listo</div>
							<div class="progress">
								<div class="progress-bar"></div>
							</div>
							<a href="#0" class="bs-wizard-dot"></a>
						</div>
					</div>
					<!-- End bs-wizard -->
					<div id="confirm">
						@if($reservation->isPendingConfirmation())

							<h4>Se completó tu reserva!</h4>
							<p>Te enviamos un email con los detalles de tu reserva, también podés verla haciendo <a href="{{ route('user.reservation', $reservation->code) }}">click acá</a>.<br/>
							Gracias por elegirnos.</p>

						@elseif($reservation->isPaymentPending())

							@if($lastPayment->isProcessing())
								<h4><i class="far fa-clock"></i>&nbsp;&nbsp;&nbsp;El pago se está procesando</h4>
								<p>
									Te notificaremos por e-mail cuando se haya completado dentro de las próx. 48 hs.<br/>
									También podés ver el estado del mismo haciendo <a href="{{ route('user.reservation', $reservation->code) }}">click acá</a>.
								</p>
							@elseif($lastPayment->isPending() && $lastPayment->isMercadoPago())
								<h4><i class="far fa-clock"></i>&nbsp;&nbsp;&nbsp;Pago pendiente</h4>
								@if($lastPayment->mercadopagoPayment->payment_type_id == 'ticket')
								<a href="{{ $lastPayment->mercadopagoPayment->ext_resource_url }}" target="_blank" class="btn btn-secondary" style="margin: 10px 0"><i class="fas fa-ticket-alt"></i> Cupón de pago</a>
								@else
								<a href="{{ $lastPayment->mercadopagoPayment->ext_resource_url }}" target="_blank" class="btn btn-secondary" style="margin: 10px 0">Instrucciones de pago</a>
								@endif
								<p>
									Abona el pago dentro de las próximas {{ \App\Reservation::RETRY_PAYMENT_TIME_HS }} horas, te mantendremos la reserva durante este tiempo.<br/>
									Podés ver el estado de la reserva haciendo <a href="{{ route('user.reservation', $reservation->code) }}">click acá</a>.
								</p>							
							@elseif($lastPayment->isFailed())
								<h4>El pago no pudo completarse</h4>
								<p>
									@if($lastPayment->isMercadoPago())

										@php($detail = $lastPayment->mercadopagoPayment->status_detail)
										
										@if($detail == 'cc_rejected_bad_filled_card_number')
											Revisa el número de la tarjeta.
										@elseif($detail == 'cc_rejected_bad_filled_date')
											Revisa la fecha de vencimiento.
										@elseif($detail == 'cc_rejected_bad_filled_other')
											Alguno de los datos de la tarjeta es incorrecto.
										@elseif($detail == 'cc_rejected_bad_filled_security_code')
											Revisa el código de seguridad.
										@elseif($detail == 'cc_rejected_call_for_authorize')
											No se pudo procesar el pago, llama a {{ ucfirst($lastPayment->mercadopagoPayment->payment_method_id) }} para autorizarlo.
										@elseif($detail == 'cc_rejected_card_disabled')
											La tarjeta no está activada. Llama a {{ ucfirst($lastPayment->mercadopagoPayment->payment_method_id) }} para activarla.
										@elseif($detail == 'cc_rejected_duplicated_payment')
											Este pago está duplicado, utiliza otro medio de pago.
										@elseif($detail == 'cc_rejected_insufficient_amount')
											La tarjeta no tiene fondos suficientes.
										@elseif($detail == 'cc_rejected_invalid_installments')
											{{ ucfirst($lastPayment->mercadopagoPayment->payment_method_id) }} no procesa la cantidad de cuotas seleccionadas.
										@elseif($detail == 'cc_rejected_max_attempts')
											Llegaste al límite de intentos permitidos. Utiliza otra tarjeta.
										@elseif($detail == 'cc_rejected_other_reason')
											{{ ucfirst($lastPayment->mercadopagoPayment->payment_method_id) }} no procesó el pago.
										@else
											No se pudo procesar el pago.
										@endif

									@endif
									<a href="{{ route('reservation.retry-payment', $reservation->code) }}">Intentalo nuevamente</a>.<br/>
									La reserva se mantendrá por {{ \App\Reservation::RETRY_PAYMENT_TIME_HS }} horas.
								</p>
							@endif

						@endif
					</div>
				</div>
			</div>
		</div>
		<!--/hero_in-->
@endsection