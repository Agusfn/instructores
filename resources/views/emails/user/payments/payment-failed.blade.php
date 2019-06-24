extends('emails.layout')


@section('content')

<p>
	{{ $user->name }}, el pago de tu reserva {{ $reservation->code }} con la tarjeta {{ $payment->mercadopagoPayment->payment_method_id }} ...{{ $payment->mercadopagoPayment->last_four_digits }} no se pudo completar.<br/>

	Motivo:
	@php($detail = $payment->mercadopagoPayment->status_detail)
	@if($detail == 'cc_rejected_bad_filled_card_number')
		Número de la tarjeta inválido.
	@elseif($detail == 'cc_rejected_bad_filled_date')
		La fecha de vencimiento inválida.
	@elseif($detail == 'cc_rejected_bad_filled_other')
		Alguno de los datos de la tarjeta es incorrecto.
	@elseif($detail == 'cc_rejected_bad_filled_security_code')
		Código de seguridad inválido.
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
	<br/><br/>
	<a href="{{ route('reservation.retry-payment', $reservation->code) }}">Reintenta el pago</a> dentro de las próximas {{ App\Reservation::RETRY_PAYMENT_TIME_HS }} horas, te guardaremos la reserva durante este tiempo.
</p>

@endsection