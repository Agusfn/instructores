@extends('admin.layouts.main')


@section('content')

    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Opciones</li>
      </ol>

      	<form action="" method="POST">
      		@csrf
			<div class="box_general padding_bottom">

				<div class="header_box version_2">
					<h2>Opciones del sitio</h2>
				</div>


				<div class="row">
					<div class="col-md-4"></div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Fecha de inicio de temporada (mes-dia)</label>
							<input type="text" class="form-control{{ $errors->has('activity_start_date') ? ' is-invalid' : '' }}" name="activity_start_date" value="{{ $settings['reservations']['activity_start_date'] }}">
						    @if ($errors->has('activity_start_date'))
						        <span class="invalid-feedback" role="alert">
						            <strong>{{ $errors->first('activity_start_date') }}</strong>
						        </span>
						    @endif
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
				
				<div class="row">
					<div class="col-md-4"></div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Fecha de fin de temporada (mes-dia)</label>
							<input type="text" class="form-control{{ $errors->has('activity_end_date') ? ' is-invalid' : '' }}" name="activity_end_date" value="{{ $settings['reservations']['activity_end_date'] }}">
						    @if ($errors->has('activity_end_date'))
						        <span class="invalid-feedback" role="alert">
						            <strong>{{ $errors->first('activity_end_date') }}</strong>
						        </span>
						    @endif
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>

				<div class="row">
					<div class="col-md-4"></div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Comisión cobrada de precio instructor (%)</label>
							<input type="text" class="form-control{{ $errors->has('service_fee_percentage') ? ' is-invalid' : '' }}" name="service_fee_percentage" value="{{ $settings['prices']['service_fee'] }}">
						    @if ($errors->has('service_fee_percentage'))
						        <span class="invalid-feedback" role="alert">
						            <strong>{{ $errors->first('service_fee_percentage') }}</strong>
						        </span>
						    @endif
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
						

			</div>
			<!-- /box_general-->
			<p><button class="btn_1 medium">Guardar</button></p>
		</form>
		
@endsection


