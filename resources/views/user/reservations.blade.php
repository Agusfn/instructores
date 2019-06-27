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
							<th>Fecha</th>
							<th>Código</th>
							<th>Estado</th>
							<th>Instructor</th>
							<th>Fecha clase</th>
							<th>Pers.</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						@if($reservations->count() == 0)
							<tr><td colspan="8" style="text-align: center;">No tienes reservas</td></tr>
						@else
							@foreach($reservations as $reservation)
							<tr>
								<td><a href="{{ url('panel/reservas/'.$reservation->code) }}"><i class="fa fa-search" aria-hidden="true"></i></a></td>
								<td>{{ $reservation->created_at->format('d/m/Y') }}</td>
								<td>{{ $reservation->code }}</td>
								<td>
									@if($reservation->isPaymentPending())
									<span class="badge badge-secondary">Pago pendiente</span>
									@elseif($reservation->isPendingConfirmation())
									<span class="badge badge-primary">Pagada - Pend. confirmación</span>
									@elseif($reservation->isFailed())
									<span class="badge badge-danger">Pago fallido</span>
									@elseif($reservation->isRejected())
									<span class="badge badge-danger">Rechazada por instructor</span>
									@elseif($reservation->isConfirmed())
									<span class="badge badge-success">Confirmada</span>
									@elseif($reservation->isConcluded())
									<span class="badge badge-danger">Concluída</span>
									@elseif($reservation->isCanceled())
									<span class="badge badge-danger">Cancelada</span>
									@endif
								</td>
								<td>{{ $reservation->instructor->name.' '.$reservation->instructor->surname[0].'.' }}</td>
								<td>{{ $reservation->reserved_class_date->format('d/m') }}&nbsp;&nbsp;&nbsp;{{ $reservation->readableHourRange(true) }}</td>
								<td>{{ $reservation->personAmount() }}</td>
								<td>${{ floatval($reservation->final_price) }}</td>
							</tr>
							@endforeach
						@endif

					</tbody>
				</table>

				{{ $reservations->links() }}

			</div>


	</div>
            
@endsection
