@extends('admin.layouts.main')


@section('content')

	<!-- Breadcrumbs-->
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="#">Panel</a>
		</li>
		<li class="breadcrumb-item active">Mi cuenta</li>
	</ol>

	<div class="row">
		<div class="col-md-6">
			

			<div class="box_general padding_bottom">


				@if(\Session::has("pwd-change-success"))
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					Contraseña cambiada exitosamente.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				@endif

				<form action="{{ url('admin/cuenta/cambiar_password') }}" method="POST">
				@csrf
					<div class="header_box version_2">
						<h2><i class="fa fa-lock"></i>Cambiar contraseña</h2>
					</div>
					<div class="form-group">
						<label>Contraseña actual</label>
						<input class="form-control{{ $errors->change_password->has('current_password') ? ' is-invalid' : '' }}" type="password" name="current_password">
						@if ($errors->change_password->has('current_password'))
				        <span class="invalid-feedback" role="alert">
				            <strong>{{ $errors->change_password->first('current_password') }}</strong>
				        </span>
				    	@endif
					</div>
					<div class="form-group">
						<label>Nueva contraseña</label>
						<input class="form-control{{ $errors->change_password->has('new_password') ? ' is-invalid' : '' }}" type="password" name="new_password">
						@if ($errors->change_password->has('new_password'))
				        <span class="invalid-feedback" role="alert">
				            <strong>{{ $errors->change_password->first('new_password') }}</strong>
				        </span>
				    	@endif
					</div>
					<div class="form-group">
						<label>Confirmar nueva contraseña</label>
						<input class="form-control {{ $errors->change_password->has('new_password_repeat') ? ' is-invalid' : '' }}" type="password" name="new_password_repeat">
						@if ($errors->change_password->has('new_password_repeat'))
				        <span class="invalid-feedback" role="alert">
				            <strong>{{ $errors->change_password->first('new_password_repeat') }}</strong>
				        </span>
				    	@endif
					</div>

					<p><button class="btn_1 medium">Guardar</button></p>
				</form>

			</div>

		</div>
	</div>
	

@endsection


