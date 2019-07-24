@extends('admin.layouts.main')


@section('custom-css')
<style type="text/css">
	.instructor-table td
	{
		vertical-align: middle;
	}

	.instructor-table .profile-pic {
		-webkit-border-radius: 50%;
		-moz-border-radius: 50%;
		-ms-border-radius: 50%;
		border-radius: 50%;
	}
</style>
@endsection

@section('content')

	<!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Panel</a>
        </li>
        <li class="breadcrumb-item active">Lista de instructores</li>
      </ol>
		<div class="box_general">
			<div class="header_box row">

				<div class="col-lg-5">
					<h2 class="d-inline-block">Instructores <small style="font-size: 13px">- {{ $instructors->total() }} resultado/s</small></h2>
				</div>

				<div class="col-lg-7">
					<div class="filter">
						<select name="order" class="selectbox" autocomplete="off">
							<option value="date_reg_desc" {{ request()->order == "date_reg_desc" ? "selected" : "" }}>Fecha ingreso (z-a)</option>
							<option value="date_reg_asc" {{ request()->order == "date_reg_asc" ? "selected" : "" }}>Fecha ingreso (a-z)</option>
							<option value="name_desc" {{ request()->order == "name_desc" ? "selected" : "" }}>Nombre (z-a)</option>
							<option value="name_asc" {{ request()->order == "name_asc" ? "selected" : "" }}>Nombre (a-z)</option>
							<option value="balance_desc" {{ request()->order == "balance_desc" ? "selected" : "" }}>Saldo (z-a)</option>
							<option value="balance_asc" {{ request()->order == "balance_asc" ? "selected" : "" }}>Saldo (a-z)</option>
						</select>
					</div>

					<div class="filter mr-lg-3">
						<select name="approval" class="selectbox" autocomplete="off">
							<option value="any" {{ request()->approval == "any" ? "selected" : "" }}>Todos</option>
							<option value="approved" {{ request()->approval == "approved" ? "selected" : "" }}>Aprobados</option>
							<option value="pending" {{ request()->approval == "pending" ? "selected" : "" }}>Pend. de aprobación</option>
						</select>
					</div>

				</div>

			</div>
			<div class="list_general">
				
				<table class="table instructor-table">

					<thead>
						<tr>
							<th></th>
							<th></th>
							<th>Nombre completo</th>
							<th>Email</th>
							<th>Aprobación</th>
							<th>Reservas</th>
							<th>Saldo</th>
						</tr>
					</thead>
					<tbody>
						@if($instructors->count() == 0)
							<tr><td colspan="7" style="text-align: center;">No hay instructores</td></tr>
						@else
							@foreach($instructors as $instructor)
							<tr>
								<td>
									<a href="{{ route('admin.instructors.details', $instructor->id) }}" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></a>
								</td>
								<td><img src="{{ $instructor->getProfilePicUrl() }}" class="profile-pic" height="60"></td>
								<td>{{ $instructor->name.' '.$instructor->surname }}</td>
								<td>{{ $instructor->email }}</td>
								<td>
									@if(!$instructor->isApproved())
										@if(!$instructor->approvalDocsSent())
										<span class="badge badge-dark">Pendiente envío documentos</span>
										@else
										<span class="badge badge-warning">Docs. enviados. Pendiente aprobacion.</span>
										@endif
									@else
										<span class="badge badge-success">Verificado</span>
									@endif
								</td>
								<td>{{ $instructor->reservations()->count() }}</td>
								<td>
									@if($instructor->isApproved())
									${{ round($instructor->wallet->balance, 2) }} ARS
									@else
									-
									@endif
								</td>
							</tr>
							@endforeach
						@endif

					</tbody>
				</table>
				
			</div>

			{{ $instructors->links() }}
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


