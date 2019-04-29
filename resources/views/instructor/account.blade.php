@extends('layouts.main')



@section('content')
	
	<section class="hero_in general start_bg_zoom"></section>
	<div class="container margin_80_55">
		


		<div class="row">

			@include('instructor.panel-nav-layout')

			<div class="col-md-9">

				@php(dump($errors))

				@if(!$instructor->isApproved())



					<div class="alert alert-warning">Tu cuenta de instructor no está verificada aún. Debés enviar la documentacion solicitada para ofrecer tus servicios de instructor.</div>

					<h4 style="margin: 20px 0">Mi cuenta</h4>

					<table class="table table-borderless">

						<tbody>
							<tr>
								<td><strong>Nombre y apellido</strong></td>
								<td>Agustín Fernandez Nuñez</td>
							</tr>
							<tr>
								<td><strong>E-mail</strong></td>
								<td>agusfn20@gmail.com</td>
							</tr>
							<tr>
								<td><strong>Contraseña</strong></td>
								<td>**********</td>
							</tr>
							<tr>
								<td><strong>Cuenta verificada</strong></td>
								<td>No</td>
							</tr>							
							<tr>
								<td><strong>Número de teléfono</strong></td>
								<td>-</td>
							</tr>
							<tr>
								<td><strong>Tipo de documento</strong></td>
								<td>-</td>
							</tr>
							<tr>
								<td><strong>Número de documento</strong></td>
								<td>-</td>
							</tr>
						</tbody>
					</table>


					<form action="{{ url('instructor/panel/cuenta/verificar') }}" method="post" enctype="multipart/form-data">
						@csrf
						<h4 style="margin: 20px 0">Verificar cuenta</h4>
						<p>Necesitamos verificar tu información para que puedas empezar a ofrecer tus servicios.</p>
						<div class="form-group">
							<label>Fotos de ambas caras del DNI, o una foto del pasaporte</label>
							<input type="file" style="display: block;" name="identification multiple="multiple">
						    @if ($errors->has('identification'))
						        <span class="invalid-feedback" role="alert" style="display: block;">
						            <strong>{{ $errors->first('identification') }}</strong>
						        </span>
						    @endif
				        </span>
						</div>
						<div class="form-group">
							<label>Fotos de ambas caras del certificado profesional</label>
							<input type="file" style="display: block;" name="certificate multiple="multiple">
						    @if ($errors->has('certificate'))
						        <span class="invalid-feedback" role="alert" style="display: block;">
						            <strong>{{ $errors->first('certificate') }}</strong>
						        </span>
						    @endif
						</div>
						<div class="form-group">
							<label>Número de teléfono</label>
							<input type="text" class="form-control" name="phone_number" style="max-width: 400px">
						    @if ($errors->has('phone_number'))
						        <span class="invalid-feedback" role="alert" style="display: block;">
						            <strong>{{ $errors->first('phone_number') }}</strong>
						        </span>
						    @endif
						</div>

						<button type="submit" class="btn btn-primary">Enviar</button>
					</form>



					<!--form>
						<div class="form-group">
							<label>Nombre</label>
							<input type="text" class="form-control" style="max-width: 400px">
						</div>
						<div class="form-group">
							<label>Apellido</label>
							<input type="text" class="form-control" style="max-width: 400px">
						</div>
						<button type="submit" class="btn btn-primary">Modificar</button>
					</form>

					<h5 style="margin: 20px 0">Cambiar contraseña</h5>

					<form>
						<div class="form-group">
							<label>Contraseña actual</label>
							<input type="password" class="form-control" style="max-width: 400px">
						</div>
						<div class="form-group">
							<label>Contraseña nueva</label>
							<input type="password" class="form-control" style="max-width: 400px">
						</div>
						<div class="form-group">
							<label>Repetir contraseña</label>
							<input type="password" class="form-control" style="max-width: 400px">
						</div>
						<button type="submit" class="btn btn-primary">Modificar</button>
					</form-->


				@endif

			</div>

		</div>


	</div>
            
@endsection
