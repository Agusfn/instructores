@extends('layouts.main')







@section('content')
	
	<section class="hero_in general start_bg_zoom"></section>
	<div class="container margin_60">
		


		<div class="row">

			@include('instructor.panel-nav-layout')

			<div class="col-9">

				<h4 class="add_bottom_30">Reservas</h4>


				<table class="table">
					<thead>
						<tr>
							<th></th>
							<th>Código</th>
							<th>Estado</th>
							<th>Cliente</th>
							<th>Fecha y hora</th>
							<th>Personas</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						@foreach($reservations as $reservation)
						<tr>
							<td><a href="{{ url('instructor/panel/reservas/'.$reservation->code) }}"><i class="fa fa-search" aria-hidden="true"></i></a></td>
							<td>{{ $reservation->code }}</td>
							<td>
								@if($reservation->status == App\Reservation::STATUS_PAYMENT_PENDING)
								Pago pendiente
								@elseif($reservation->status == App\Reservation::STATUS_UNPAID)
								Impago
								@elseif($reservation->status == App\Reservation::STATUS_PENDING_CONFIRMATION)
								Pagada - confirmar
								@elseif($reservation->status == App\Reservation::STATUS_REJECTED)
								Rechazada
								@elseif($reservation->status == App\Reservation::STATUS_CONFIRMED)
								Confirmada
								@endif
								
							</td>
							<td>{{ $reservation->user->name.' '.$reservation->user->surname }}</td>
							<td>{{ date('d M', strtotime($reservation->reserved_date)).', '.$reservation->reserved_time_start.'-'.$reservation->reserved_time_end.'hs' }}</td>
							<td>{{ $reservation->persons_amount }}</td>
							<td>${{ floatval($reservation->final_price) }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>

			</div>

		</div>


	</div>
            
@endsection
