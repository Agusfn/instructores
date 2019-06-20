@extends('layouts.main')


@section('title', 'Mis reservas')

@section('content')

	<section class="hero_in general start_bg_zoom"></section>
	<div class="container margin_80_55">
		

		<div class="row">

			@include('user.panel-nav-layout')

			<div class="col-md-9">

				<h4 class="add_bottom_30">Reservas</h4>


				<table class="table">
					<thead>
						<tr>
							<th></th>
							<th>Código</th>
							<th>Estado</th>
							<th>Instructor</th>
							<th>Fecha</th>
							<th>Horas</th>
							<th>Pers.</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						@foreach($reservations as $reservation)
						<tr>
							<td><a href="{{ url('panel/reservas/'.$reservation->code) }}"><i class="fa fa-search" aria-hidden="true"></i></a></td>
							<td>{{ $reservation->code }}</td>
							<td>
								@if($reservation->isPaymentPending())
								Pago pendiente
								@elseif($reservation->isPendingConfirmation())
								Pagada - Pend. confirmación
								@elseif($reservation->isFailed())
								Pago fallido
								@elseif($reservation->isRejected())
								Rechazada
								@elseif($reservation->isConfirmed())
								Confirmada
								@elseif($reservation->isCanceled())
								@endif
							</td>
							<td>{{ $reservation->instructor->name.' '.$reservation->instructor->surname[0].'.' }}</td>
							<td>{{ $reservation->reserved_class_date->format('d/m') }}</td>
							<td>{{ $reservation->reserved_time_start.'-'.$reservation->reserved_time_end.'hs' }}</td>
							<td>{{ $reservation->adults_amount + $reservation->kids_amount }}</td>
							<td>${{ floatval($reservation->final_price) }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>

			</div>


	</div>
            
@endsection
