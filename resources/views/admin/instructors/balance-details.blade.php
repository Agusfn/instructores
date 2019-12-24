@extends('admin.layouts.main')


@section('custom-css')
<style type="text/css">

</style>
@endsection



@section('content')

	<!-- Breadcrumbs-->
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Panel</a></li>
			<li class="breadcrumb-item active">Lista de instructores</li>
			<li class="breadcrumb-item active">Detalles de servicio</li>
		</ol>


		<ul class="nav nav-pills margin_bottom" style="margin-bottom: 20px">
			<li class="nav-item">
				<a class="nav-link" href="{{ route('admin.instructors.account-details', $instructor->id) }}">Cuenta</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{ route('admin.instructors.service-details', $instructor->id) }}">Servicio y publicación</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active" href="{{ route('admin.instructors.balance-details', $instructor->id) }}">Saldo y dinero</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{ route('admin.instructors.review-details', $instructor->id) }}">Reseñas</a>
			</li>
		</ul>


		<div class="box_general padding_bottom">
			<div class="header_box">
				<h2 class="d-inline-block">Cuentas para retiro de dinero</h2>
			</div>
			<div class="list_general">
				
				<div class="row">

					<div class="col-md-6">
						<label>Cuenta bancaria:</label>
						@if($instructor->bankAccount)
						<div>
							<strong>CBU:</strong> {{ $instructor->bankAccount->cbu }}<br/>
							<strong>Titular:</strong> {{ $instructor->bankAccount->holder_name }}<br/>
							<strong>Documento:</strong> {{ $instructor->bankAccount->document_number }}<br/>
							<strong>CUIL/CUIT:</strong> {{ $instructor->bankAccount->cuil_cuit }}<br/>
						</div>
						@else
						No
						@endif
					</div>

					<div class="col-md-6">
						<label>Cuenta mercadopago:</label>
						@if($instructor->mpAccount)
						<br/>
						{{ $instructor->mpAccount->email }}
						@else
						No
						@endif
					</div>

				</div>
				
			</div>
		</div>
		
		@if($instructor->isApproved())
		<div class="box_general padding_bottom">
			<div class="header_box">
				<h2 class="d-inline-block">Balance</h2>
				<h2 style="float: right; color: #5292e6 !important;">${{ $instructor->wallet->balance }}</h2>
			</div>
			<div class="list_general">
				
				<h6>Transacciones</h6>
				<table class="table">
					<thead>
						<tr>
							<th>ID</th>
							<th>Fecha</th>
							<th>Concepto</th>
							<th>Reserva</th>
							<th>Monto</th>
							<th>Saldo</th>
						</tr>
					</thead>
					<tbody>
						@foreach($walletMovements as $movement)
						<tr>
							<td>{{ $movement->id }}</td>
							<td>{{ $movement->date->format('d/m/Y') }}</td>
							<td>
								@if($movement->motive == App\InstructorWalletMovement::MOTIVE_RESERVATION_PAYMENT)
								Pago de reserva
								@elseif($movement->motive == App\InstructorWalletMovement::MOTIVE_COLLECTION)
								Retiro de dinero
								@endif
							</td>
							<td>
								@if($movement->motive == App\InstructorWalletMovement::MOTIVE_RESERVATION_PAYMENT)
								<a href="{{ route('admin.reservations.details', $movement->reservation->id) }}">#{{ $movement->reservation->code }}</a>
								@endif
							</td>
							<td>${{ round($movement->net_amount, 2) }}</td>
							<td>${{ round($movement->new_balance, 2) }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>

				
			</div>
			{{ $walletMovements->links() }}
		</div>
		@endif

	
@endsection



@section('custom-js')
@endsection