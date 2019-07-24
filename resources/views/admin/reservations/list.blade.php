@extends('admin.layouts.main')


@section('custom-css')
<style type="text/css">
	
	{{--/*.filter-dropdown-group {
		float: right;
		margin-right: 20px;
	}

	.filter-dropdown-group .dropdown-menu {
		min-width: 220px;
		font-size: 13px;
	}

	.filter-dropdown-group .dropdown-menu .form-check-input {
		margin-left: 0;
	}*/--}}

</style>

@endsection

@section('content')

	<!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Panel</a>
        </li>
        <li class="breadcrumb-item active">Lista de reservas</li>
      </ol>
		<div class="box_general">
			<div class="header_box row">

				<div class="col-lg-5">
					<h2 class="d-inline-block">Reservas <small style="font-size: 13px">- {{ $reservations->total() }} resultado/s</small></h2>
				</div>

				<div class="col-lg-7">
					<div class="filter">
						<select name="order" class="selectbox" autocomplete="off">
							<option value="date_desc" {{ request()->order == "date_desc" ? "selected" : "" }}>Fecha reservado (z-a)</option>
							<option value="date_asc" {{ request()->order == "date_asc" ? "selected" : "" }}>Fecha reservado (a-z)</option>
							<option value="class_date_desc" {{ request()->order == "class_date_desc" ? "selected" : "" }}>Fecha clases (z-a)</option>
							<option value="class_date_asc" {{ request()->order == "class_date_asc" ? "selected" : "" }}>Fecha clases (a-z)</option>
							<option value="price_desc" {{ request()->order == "price_desc" ? "selected" : "" }}>Precio (z-a)</option>
							<option value="price_asc" {{ request()->order == "price_asc" ? "selected" : "" }}>Precio (a-z)</option>
						</select>
					</div>

					<div class="filter mr-lg-3">
						<select name="discipline" class="selectbox" autocomplete="off">
							<option value="any" {{ request()->discipline == "any" ? "selected" : "" }}>Cualquier disciplina</option>
							<option value="ski" {{ request()->discipline == "ski" ? "selected" : "" }}>Ski</option>
							<option value="snowboard" {{ request()->discipline == "snowboard" ? "selected" : "" }}>Snowboard</option>
						</select>
					</div>


					<div class="filter mr-lg-1">
						<select name="status" class="selectbox" autocomplete="off">
							<option value="any" {{ request()->status == "any" ? "selected" : "" }}>Cualquier estado</option>
							<option value="payment_pending" {{ request()->status == "payment_pending" ? "selected" : "" }}>Pago pendiente</option>
							<option value="pending_confirmation" {{ request()->status == "pending_confirmation" ? "selected" : "" }}>Pendiente confirmación</option>
							<option value="payment_failed" {{ request()->status == "payment_failed" ? "selected" : "" }}>Pago fallido</option>
							<option value="rejected" {{ request()->status == "rejected" ? "selected" : "" }}>Rechazada</option>
							<option value="confirmed" {{ request()->status == "confirmed" ? "selected" : "" }}>Confirmada</option>
							<option value="concluded" {{ request()->status == "concluded" ? "selected" : "" }}>Concluida</option>
							<option value="canceled" {{ request()->status == "canceled" ? "selected" : "" }}>Cancelada</option>
						</select>
					</div>
				</div>


				{{--<!--div class="btn-group filter-dropdown-group">
					<button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Estado</button>
					<div class="dropdown-menu">
						<div class="p-3">
							<div class="mb-1">
								<input type="checkbox" class="form-check-input" id="dropdownCheck1">
								<label class="form-check-label" for="dropdownCheck1">Pago pendiente</label>
							</div>
							<div class="mb-1">
								<input type="checkbox" class="form-check-input" id="dropdownCheck1">
								<label class="form-check-label" for="dropdownCheck1">Pendiente confirmación</label>
							</div>
							<div class="mb-1">
								<input type="checkbox" class="form-check-input" id="dropdownCheck1">
								<label class="form-check-label" for="dropdownCheck1">Pago fallido</label>
							</div>
							<div class="mb-1">
								<input type="checkbox" class="form-check-input" id="dropdownCheck1">
								<label class="form-check-label" for="dropdownCheck1">Rechazado</label>
							</div>
							<div class="mb-1">
								<input type="checkbox" class="form-check-input" id="dropdownCheck1">
								<label class="form-check-label" for="dropdownCheck1">Confirmado</label>
							</div>
							<div class="mb-1">
								<input type="checkbox" class="form-check-input" id="dropdownCheck1">
								<label class="form-check-label" for="dropdownCheck1">Concluido</label>
							</div>
							<div class="mb-3">
								<input type="checkbox" class="form-check-input" id="dropdownCheck1">
								<label class="form-check-label" for="dropdownCheck1">Cancelado</label>
							</div>
							<button class="btn btn-sm btn-primary">Aplicar</button>
						</div>
					</div>
				</div-->--}}

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
						@if($reservations->count() == 0)
							<tr><td colspan="9" style="text-align: center;">No hay reservas</td></tr>
						@else
							@foreach($reservations as $reservation)

							<tr>
								<td><a href="{{ route('admin.reservations.details', $reservation->id) }}" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></a></td>
								<td>{{ $reservation->code }}</td>
								<td>{{ $reservation->created_at->format('d/m/Y') }}</td>
								<td>
									@if($reservation->isPaymentPending())
										<span class="badge badge-secondary">Pago pendiente -
										@if($reservation->lastPayment) {{-- in case reservation was just created but payment being processed --}}
											@if($reservation->lastPayment->isPending()) 
											Efectivo
											@elseif($reservation->lastPayment->isProcessing())
											Procesando
											@elseif($reservation->lastPayment->isFailed())
											Reintentar
											@endif
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
						@endif


					</tbody>
				</table>
			</div>
			{{ $reservations->links() }}

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
		
		<!-- /pagination-->

@endsection


@section('custom-js')
<script type="text/javascript">
	$(document).ready(function() {



		{{--/*$('.filter-dropdown-group .dropdown-menu').on('click', function(e) {
		  e.stopPropagation();
		});*/--}}

	});
</script>
@endsection