@extends('layouts.main')



@section('content')
	
	<section class="hero_in general start_bg_zoom"></section>
	<div class="container margin_60">
		


		<div class="row">

			@include('instructor.panel-nav-layout')

			<div class="col-md-9">

				@if(!$instructor->isApproved())
					@if(!$instructor->approvalDocsSent())
					<div class="alert alert-warning">Tu cuenta de instructor no está verificada aún. Debés enviar la documentacion solicitada para ofrecer tus servicios de instructor.</div>
					@else
					<div class="alert alert-info">Se ha enviado la documentación para verificar la cuenta. Estarás recibiendo un e-mail dentro de las siguientes 24hs de haberla enviado cuando la hayamos verificado.</div>
					@endif
				@endif

				<h4 style="margin-bottom: 20px">Mi cuenta</h4>

				<table class="table table-borderless">

					<tbody>
						<tr>
							<td><strong>Nombre y apellido</strong></td>
							<td>{{ $instructor->name.' '.$instructor->surname }}</td>
						</tr>
						<tr>
							<td><strong>E-mail</strong></td>
							<td>{{ $instructor->email }}</td>
						</tr>
						<tr>
							<td><strong>Contraseña</strong></td>
							<td>********** <span style="font-size:12px">(<a href="{{ url('instructor/panel/cuenta/password') }}">cambiar</a>)</span></td>
						</tr>
						<tr>
							<td><strong>Cuenta verificada</strong></td>
							<td>
								@if($instructor->isApproved())
								Sí
								@else
									@if($instructor->approvalDocsSent())
									Pendiente
									@else
									No
									@endif
								@endif
							</td>
						</tr>							
						<tr>
							<td><strong>Número de teléfono</strong></td>
							<td>
								@if($instructor->phone_number)
								<div>
									{{ $instructor->phone_number }}
									<span style="font-size:12px">(<a href="javascript:void(0);" onclick="$(this).closest('div').hide();$('#change-phone-form').show();">cambiar</a>)</span>
								</div>
							
								<form id="change-phone-form" style="display: none" method="POST" action="{{ url('instructor/panel/cuenta/cambiar_tel') }}">
									@csrf
									<input type="text" value="{{ $instructor->phone_number }}" name="phone_number" class="form-control form-control-sm" style="width: 150px; display: inline-block;">
									<button type="submit" class="btn btn-primary btn-sm">Modificar</button>
								</form>
								@else
								-
								@endif	
							</td>
						</tr>
						<tr>
							<td><strong>Tipo de documento</strong></td>
							<td>
								@if($instructor->isApproved())
								{{ App\Instructor::idTypeName($instructor->identification_type) }}
								@else
								-
								@endif
							</td>
						</tr>
						<tr>
							<td><strong>Número de documento</strong></td>
							<td>
								@if($instructor->isApproved())
								{{ $instructor->identification_number }}
								@else
								-
								@endif
							</td>
						</tr>
					</tbody>
				</table>

				@if(!$instructor->approvalDocsSent())
				<form action="{{ url('instructor/panel/cuenta/verificar') }}" method="post" enctype="multipart/form-data">
					@csrf
					<h4 style="margin: 20px 0">Verificar cuenta</h4>
					<p>Necesitamos verificar tu identidad y certificación para que puedas empezar a ofrecer tus servicios.</p>
					<div class="form-group">
						<label>Fotos de ambas caras del DNI, o una foto del pasaporte</label>
						<input type="file" style="display: block;" name="identification_imgs[]" multiple="multiple">
					    @if ($errors->has('identification_imgs'))
					        <span class="invalid-feedback" role="alert" style="display: block;">
					            <strong>{{ $errors->first('identification_imgs') }}</strong>
					        </span>
					    @elseif ($errors->has('identification_imgs.*'))
					        <span class="invalid-feedback" role="alert" style="display: block;">
					            <strong>Sólo se pueden subir imágenes y de hasta 5MB cada una.</strong>
					        </span>
					    @endif
			        </span>
					</div>
					<div class="form-group">
						<label>Fotos de ambas caras del certificado profesional</label>
						<input type="file" style="display: block;" name="certificate_imgs[]" multiple="multiple">
					    @if ($errors->has('certificate_imgs'))
					        <span class="invalid-feedback" role="alert" style="display: block;">
					            <strong>{{ $errors->first('certificate_imgs') }}</strong>
					        </span>
					    @elseif ($errors->has('identification_imgs.*'))
					        <span class="invalid-feedback" role="alert" style="display: block;">
					            <strong>Sólo se pueden subir imágenes y de hasta 5MB cada una.</strong>
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
				@endif


			</div>

		</div>


	</div>
            
@endsection
