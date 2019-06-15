@extends('layouts.main')

@section('title', 'Mi cuenta')

@section('custom-css')
<link rel="stylesheet" href="{{ asset('resources/vendor/croppie/croppie.css') }}" />
<style type="text/css">
	.profile-pic {
		width: 150px;
		height: 150px;
		border-top-left-radius: 50% 50%;
		border-top-right-radius: 50% 50%;
		border-bottom-right-radius: 50% 50%;
		border-bottom-left-radius: 50% 50%;
	}
</style>
@endsection

@section('content')

	<section class="hero_in general start_bg_zoom"></section>
	<div class="container margin_80_55">
		


		<div class="row">

			@include('user.panel-nav-layout')

			<div class="col-md-9">

				<h4 class="add_bottom_30">Mi cuenta <span style="font-size: 14px;font-weight: normal;">- <a href="{{ url('panel/cuenta/modificar') }}">Actualizar datos</a></span></h4>

				<div class="more_padding_left add_bottom_60">
					
					<div class="row add_bottom_30">
						<div class="col-6"><strong>Login cuenta</strong></div>
						<div class="col-6">{{ ucfirst($user->provider) }}</div>
					</div>
					<div class="row add_bottom_30">
						<div class="col-6"><strong>Nombre y apellido</strong></div>
						<div class="col-6">{{ $user->name.' '.$user->surname }}</div>
					</div>
					<div class="row add_bottom_30">
						<div class="col-6"><strong>E-mail</strong></div>
						<div class="col-6">{{ $user->email }}</div>
					</div>
					<div class="row add_bottom_30">
						<div class="col-6"><strong>Número de teléfono</strong></div>
						<div class="col-6">
							@if($user->phone_number)
							{{ $user->phone_number }}
							@else
							<a href="{{ url('panel/cuenta/modificar') }}">Agregar</a>
							@endif
						</div>
					</div>
					<div class="row add_bottom_30">
						<div class="col-6"><strong>Foto de perfil</strong></div>
						<div class="col-6">

							<input type="file" id="profile-pic-input" accept="image/*" autocomplete="off" @if($user->profile_picture) style="display: none" @endif>

							@if($user->profile_picture)
							<div style="width: 150px;text-align: center;">
								<img src="{{ $user->getProfilePicUrl() }}" class="profile-pic"><br/>
								<a href="javascript:void(0);" id="change-profile-pic">Cambiar</a>
							</div>
							@endif
						</div>
					</div>

					<div class="row add_bottom_30" id="img-crop-box" style="display: none">
						<div class="col-sm-10">
							<div class="img-crop"></div>
						</div>

						<div class="col-sm-2">
							<button type="button" class="btn btn-default btn-primary" id="upload-profile-pic" style="position: absolute; bottom: 0">Cargar</button>
						</div>
					</div>
				</div>

			</div>

		</div>


	</div>
            
@endsection



@section('custom-js')
<script src="{{ asset('resources/vendor/croppie/croppie.min.js') }}"></script>
<script src="{{ asset('resources/js/user-account-pg.js') }}"></script>

<script>
var app_url = "{{ config('app.url').'/' }}";
</script>
@endsection