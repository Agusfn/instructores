@extends('admin.layouts.main')


@section('content')

	<!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Bookings list</li>
      </ol>
		<div class="box_general">
			<div class="header_box">
				<h2 class="d-inline-block">Reservas</h2>
				<!--div class="filter">
					<select name="orderby" class="selectbox">
						<option value="Any status">Any status</option>
						<option value="Approved">Approved</option>
						<option value="Pending">Pending</option>
						<option value="Cancelled">Cancelled</option>
					</select>
				</div-->
			</div>
			<div class="list_general">
				
				<table class="table">
					<thead>
						<tr>
							<th></th>
							<th>Cod.</th>
							<th>Fecha realiz.</th>
							<th>Estado</th>
							<th>Cliente</th>
							<th>Instructor</th>
							<th>Fecha y hora</th>
							<th>Pers.</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						
						@foreach($reservations as $reservation)

						<tr>
							<td><a href="{{ route('admin.reservations.details', $reservation->id) }}" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></a></td>
							<td>{{ $reservation->code }}</td>
							<td>{{ $reservation->created_at->format('d/m/Y') }}</td>
							<td>
								@if($reservation->isPaymentPending())
									<span class="badge badge-secondary">Pago pendiente - 
									@if($reservation->lastPayment->isProcessing())
									Procesando
									@elseif($reservation->lastPayment->isFailed())
									Reintentar
									@endif
									</span>
								@elseif($reservation->isPendingConfirmation())
									<span class="badge badge-primary">Pend. confirmación instructor</span>
								@elseif($reservation->isFailed())
									<span class="badge badge-danger">Pago fallido</span>
								@elseif($reservation->isRejected())
									<span class="badge badge-danger">Rechazada por instructor</span>
								@elseif($reservation->isConfirmed())
									<span class="badge badge-success">Confirmada</span>
								@elseif($reservation->isConcluded())
									<span class="badge badge-success">Concluída</span>
								@elseif($reservation->isCanceled())
									<span class="badge badge-danger">Cancelada</span>
								@endif
							</td>
							<td>
								<a href="{{ route('admin.users.details', $reservation->user->id) }}">
								{{ $reservation->user->name.' '.$reservation->user->surname }}
								</a>
							</td>
							<td>
								<a href="{{ route('admin.instructors.details', $reservation->instructor->id) }}">
								{{ $reservation->instructor->name.' '.$reservation->instructor->surname }}
								</a>
							</td>
							<td>{{ $reservation->reserved_class_date->format('d/m/Y') }}&nbsp;&nbsp;&nbsp;{{ $reservation->readableHourRange(true) }}</td>
							<td>{{ $reservation->personAmount() }}</td>
							<td>${{ round($reservation->final_price, 2) }}</td>
						</tr>

						@endforeach

					</tbody>
				</table>

			</div>
		</div>
		<!-- /box_general-->
		<!--nav aria-label="...">
			<ul class="pagination pagination-sm add_bottom_30">
				<li class="page-item disabled">
					<a class="page-link" href="#" tabindex="-1">Previous</a>
				</li>
				<li class="page-item"><a class="page-link" href="#">1</a></li>
				<li class="page-item"><a class="page-link" href="#">2</a></li>
				<li class="page-item"><a class="page-link" href="#">3</a></li>
				<li class="page-item">
					<a class="page-link" href="#">Next</a>
				</li>
			</ul>
		</nav-->
		{{ $reservations->links() }}
		<!-- /pagination-->

@endsection


