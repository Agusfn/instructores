@extends('admin.layouts.main')


@section('content')

	<!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Panel</a>
        </li>
        <li class="breadcrumb-item active">Pagos a instructores</li>
      </ol>
		<div class="box_general">
			<div class="header_box row">

				<div class="col-lg-5">
					<h2 class="d-inline-block">Solicitudes de extraccion de instructores <small style="font-size: 13px">- {{ $collections->total() }} resultado/s</small></h2>
				</div>

				<div class="col-lg-7">
					<div class="filter">
						<select name="order" class="selectbox" autocomplete="off">
							<option value="date_req_desc" {{ request()->order == "date_req_desc" ? "selected" : "" }}>Fecha solicitado (z-a)</option>
							<option value="date_req_asc" {{ request()->order == "date_req_asc" ? "selected" : "" }}>Fecha solicitado (a-z)</option>
							<option value="amount_desc" {{ request()->order == "amount_desc" ? "selected" : "" }}>Monto (z-a)</option>
							<option value="amount_asc" {{ request()->order == "amount_asc" ? "selected" : "" }}>Monto (a-z)</option>
						</select>
					</div>

					<div class="filter mr-lg-3">
						<select name="collect_method" class="selectbox" autocomplete="off">
							<option value="any" {{ request()->collect_method == "any" ? "selected" : "" }}>Cualquier medio</option>
							<option value="mercadopago" {{ request()->collect_method == "mercadopago" ? "selected" : "" }}>Mercadopago</option>
							<option value="bank" {{ request()->collect_method == "bank" ? "selected" : "" }}>Cuenta bancaria</option>
						</select>
					</div>	

					<div class="filter mr-lg-1">
						<select name="status" class="selectbox" autocomplete="off">
							<option value="any" {{ request()->status == "any" ? "selected" : "" }}>Cualquier estado</option>
							<option value="pending" {{ request()->status == "pending" ? "selected" : "" }}>Pendiente</option>
							<option value="completed" {{ request()->status == "completed" ? "selected" : "" }}>Completado</option>
							<option value="rejected" {{ request()->status == "rejected" ? "selected" : "" }}>Rechazado</option>
							<option value="canceled" {{ request()->status == "canceled" ? "selected" : "" }}>Cancelado</option>
						</select>
					</div>

				</div>



			</div>
			<div class="list_general">
				
				<table class="table">
					<thead>
						<tr>
							<th>Id</th>
							<th>Fecha solicitado</th>
							<th>Instructor</th>
							<th>Estado</th>
							<th>Monto</th>
							<th>Medio de retiro</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@if($collections->count() == 0)
							<tr><td colspan="7" style="text-align: center;">No hay pagos a instructores para mostrar</td></tr>
						@else
							@foreach($collections as $collection)
							<tr>
								<td>{{ $collection->id }}</td>
								<td>{{ $collection->created_at->format('d/m/Y') }}</td>
								@php($instructor = $collection->instructorWallet->instructor)
								<td><a href="{{ route('admin.instructors.details', $instructor->id) }}">{{ $instructor->name.' '.$instructor->surname }}</a></td>
								<td style="font-size: 15px">
									@if($collection->isPending())
									<span class="badge badge-primary">Pendiente</span>
									{{--@elseif($collection->isInProcess())
									<span class="badge badge-info">Procesando</span>--}}
									@elseif($collection->isCompleted())
									<span class="badge badge-success">Completado</span>
									@elseif($collection->isRejected())
										@if($collection->reject_reason)
										<span class="badge badge-danger" data-toggle="tooltip" data-placement="top" title="{{ $collection->reject_reason }}" style="text-decoration: underline dotted;">Rechazado</span>
										@else
										<span class="badge badge-danger">Rechazado</span>
										@endif
									@elseif($collection->isCanceled())
									<span class="badge badge-secondary">Cancelado por instructor</span>
									@endif
								</td>
								<td>
									{{ $collection->amount }} ARS
								</td>
								
								<td>
									@if($collection->isPending())
										@if($collection->isToBank())
										@php($bankAccount = $instructor->bankAccount)
										<a href="javascript:void(0);" onclick="alert('{{ 'CBU '.$bankAccount->cbu.'\nTitular: '.$bankAccount->holder_name.'\n Doc: '.$bankAccount->document_number.'\n CUIL/CUIT: '.$bankAccount->cuil_cuit }}')">Cuenta bancaria</a>
										@else
										<a href="javascript:void(0);" onclick="alert('Email cuenta: {{ $instructor->mpAccount->email }}');">MercadoPago</a>
										@endif
									@else
										@if($collection->isToBank()) Cuenta bancaria @else MercadoPago @endif
									@endif

								</td>
								<td>
									@if($collection->isPending())
									<ul>
										<li style="display: inline-block;">
											<form action="{{ url('admin/pagos-instructores/confirmar') }}" method="POST">
												@csrf
												<button type="button" class="btn_1 gray approve" onclick="if(confirm('¿Ya fue el dinero enviado a la cuenta correspondiente? ¿Confirmar?')) $(this).parent().submit();"><i class="fa fa-fw fa-check-circle-o"></i> Confirmar</button>
												<input type="hidden" name="collection_id" value="{{ $collection->id }}">
											</form>
										</li>
										<li style="display: inline-block;">
											<form action="{{ url('admin/pagos-instructores/rechazar') }}" method="POST">
												@csrf
												<button type="button" class="btn-reject btn_1 gray delete"><i class="fa fa-fw fa-times-circle-o"></i> Rechazar</button>
												<input type="hidden" name="reason">
												<input type="hidden" name="collection_id" value="{{ $collection->id }}">
											</form>
										</li>
									</ul>
									@endif
								</td>
							</tr>
							@endforeach
						@endif


					</tbody>
				</table>
				
			</div>

			{{ $collections->appends(request()->input())->links() }}
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

	$(".btn-reject").click(function() {
		var reason = prompt("Ingrese motivo de rechazo");

		if(reason == null || !confirm("¿Continuar?"))
			return;

		$(this).next().val(reason);
		$(this).parent().submit();
	});
});
</script>
@endsection