@extends('admin.layouts.main')


@section('custom-css')
<style type="text/css">
	.users-table td
	{
		vertical-align: middle;
	}

	.users-table .profile-pic {
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
        <li class="breadcrumb-item active">Lista de usuarios</li>
      </ol>
		<div class="box_general">
			<div class="header_box row">

				<div class="col-lg-5">
					<h2 class="d-inline-block">Usuarios <small style="font-size: 13px">- {{ $users->total() }} resultado/s</small></h2>
				</div>

				<div class="col-lg-7">
					<div class="filter">
						<select name="order" class="selectbox" autocomplete="off">
							<option value="date_reg_desc" {{ request()->order == "date_reg_desc" ? "selected" : "" }}>Fecha ingreso (z-a)</option>
							<option value="date_reg_asc" {{ request()->order == "date_reg_asc" ? "selected" : "" }}>Fecha ingreso (a-z)</option>
							<option value="name_desc" {{ request()->order == "name_desc" ? "selected" : "" }}>Nombre (z-a)</option>
							<option value="name_asc" {{ request()->order == "name_asc" ? "selected" : "" }}>Nombre (a-z)</option>
						</select>
					</div>

				</div>

			</div>
			<div class="list_general">
				
				<table class="table users-table">

					<thead>
						<tr>
							<th></th>
							<th></th>
							<th>ID</th>
							<th>Nombre completo</th>
							<th>Email</th>
							<th>Reservas</th>
						</tr>
					</thead>
					<tbody>
						@if($users->count() == 0)
							<tr><td colspan="6" style="text-align: center;">No hay usuarios</td></tr>
						@else
							@foreach($users as $user)
							<tr>
								<td><a href="{{ route('admin.users.details', $user->id) }}" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></a></td>
								<td><img src="{{ $user->getProfilePicUrl() }}" class="profile-pic" height="60"></td>
								<td>{{ $user->id }}</td>
								<td>{{ $user->name.' '.$user->surname }}</td>
								<td>
									{{ $user->email }}
									@if(!$user->hasSocialLogin() && !$user->hasVerifiedEmail())
									<span class="badge badge-warning">Pend. verif</span>
									@endif
								</td>
								<td>{{ $user->reservations()->count() }}</td>
							</tr>
							@endforeach
						@endif

					</tbody>
				</table>

			</div>
			{{ $users->appends(request()->input())->links() }}
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


