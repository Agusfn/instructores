@extends('layouts.main')

@section('title', 'Mis pagos')




@section('content')
	
	<section class="hero_in general start_bg_zoom"></section>
	<div class="container margin_60">
		


		<div class="row">

			@include('instructor.panel-nav-layout')

			<div class="col-md-9">

				<h4 class="add_bottom_30">
					Mis cobros
					<!--<div style="float:right; color: #2e79b9">$14610.50</div>-->
				</h4>

				@include('layouts.errors')

				@if(\Session::has("mp_assoc_success"))
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					Cuenta asociada exitosamente.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				@endif

				@if($mpAccount == null || $mpAccount->access_token == null)

				<p>Asociá tu cuenta de MercadoPago con la cual vas a recibir los pagos del servicio que vas a ofrecer. Deberás iniciar sesión con tu cuenta de MercadoPago o registrar una si no tenés.</p>
				<form action="{{ url('instructor/panel/cobros/url_asoc_mp') }}" method="POST">
					@csrf
					<button class="btn btn-success">Asociar cuenta de MercadoPago</button>
				</form>
				@else
				<table class="table">

					<thead>
						<tr>
							<th>Fecha</th>
							<th>Concepto</th>
							<th>Reserva</th>
							<th>Monto</th>
							<th>Saldo</th>
						</tr>
					</thead>
					<tbody>
						@foreach($balanceMovements as $movement)
						<tr>
							<td>{{ date('d/m/Y', strtotime($movement->date)) }}</td>
							<td>
								@if($movement->motive == App\InstructorBalanceMovement::MOTIVE_RESERVATION_PAYMENT)
								Pago de reserva
								@elseif($movement->motive == App\InstructorBalanceMovement::MOTIVE_COLLECTION)
								Retiro de dinero
								@endif
							</td>
							<td>
								@if($movement->motive == App\InstructorBalanceMovement::MOTIVE_RESERVATION_PAYMENT)
								<a href="">{{ $movement->reservation->code }}</a>
								@endif
							</td>
							<td>${{ floatval($movement->net_ammount) }}</td>
							<td></td>
						</tr>
						@endforeach
					</tbody>
				</table>

				@endif




			</div>

		</div>


	</div>
            
@endsection


