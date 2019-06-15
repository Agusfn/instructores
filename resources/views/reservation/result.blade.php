@extends('layouts.main')

@section('title', 'Resultado reserva')


@section('custom-css')
<style type="text/css">
	.hero_in .wrapper a {
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

						<div class="bs-wizard-step @if($payment->isFailed()) disabled @endif">
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
							<p>Te enviamos un email con los detalles de tu reserva. Gracias por elegirnos.</p>
						@elseif($reservation->isPaymentPending())
							@if($payment->status == App\ReservationPayment::STATUS_PROCESSING)
							<h4><i class="far fa-clock"></i>&nbsp;&nbsp;&nbsp;El pago se está procesando</h4>
							<p>Te notificaremos por e-mail cuando se haya completado.</p>
							@elseif($payment->status == App\ReservationPayment::STATUS_FAILED)
							<h4>El pago no pudo completarse</h4>
							<!-- Detallar error!!! -->
							<p>Hubo un error realizando el pago. <a href="">Intentalo nuevamente</a>.<br/>
							La clase quedará reservada por 24horas.</p>
							@endif
						@endif
					</div>
				</div>
			</div>
		</div>
		<!--/hero_in-->
@endsection