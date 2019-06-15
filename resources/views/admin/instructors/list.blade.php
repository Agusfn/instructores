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
          <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Lista de instructores</li>
      </ol>
		<div class="box_general">
			<div class="header_box">
				<h2 class="d-inline-block">Lista de instructores</h2>
				<div class="filter">
					<select name="orderby" class="selectbox">
						<option value="Any status">Any status</option>
						<option value="Approved">Approved</option>
						<option value="Pending">Pending</option>
						<option value="Cancelled">Cancelled</option>
					</select>
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
							<th>Verificado</th>
							<th>Reservas</th>
							<th>Saldo</th>
						</tr>
					</thead>
					<tbody>
						@foreach($instructors as $instructor)
						<tr>
							<td>
								<a href="{{ route('admin.instructors.details', $instructor->id) }}" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></a>
							</td>
							<td><img src="{{ $instructor->getProfilePicUrl() }}" class="profile-pic" height="60"></td>
							<td>{{ $instructor->name.' '.$instructor->surname }}</td>
							<td>{{ $instructor->email }}</td>
							<td>
								@if(!$instructor->hasVerifiedEmail())
									<span class="badge badge-secondary">Pendiente verif email</span>
								@elseif(!$instructor->isApproved())
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
							<td>${{ $instructor->balance }} ARS</td>
						</tr>
						@endforeach
					</tbody>
				</table>

			</div>
		</div>
		<!-- /box_general-->
		<nav aria-label="...">
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
		</nav>
		<!-- /pagination-->

@endsection


