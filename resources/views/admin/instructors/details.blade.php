@extends('admin.layouts.main')



@section('body-start')

@if($instructor != null)

	@if(!$instructor->isApproved() && $instructor->approvalDocsSent())
	<div class="modal" tabindex="-1" role="dialog" id="approval-modal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Aprobar instructor</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<div class="alert alert-info">Ingresa los datos de los documentos enviados por el instructor.</div>

					<form action="{{ url('admin/instructores/'.$instructor->id.'/aprobar') }}" method="POST" id="approve-form">
						@csrf
						<div class="form-group">
							<label>Tipo de documento</label>
							<select name="identification_type" class="form-control">
								<option value="dni">DNI</option>
								<option value="passport">Pasaporte</option>
							</select>
							@if ($errors->approval->has('identification_type'))
					        <span class="invalid-feedback" role="alert" style="display: block;">
					            <strong>{{ $errors->approval->first('identification_type') }}</strong>
					        </span>
					    	@endif
						</div>
						<div class="form-group">
							<label>Número de documento</label>
							<input type="text" class="form-control" name="identification_number">
							@if ($errors->approval->has('identification_number'))
					        <span class="invalid-feedback" role="alert" style="display: block;">
					            <strong>{{ $errors->approval->first('identification_number') }}</strong>
					        </span>
					    	@endif
						</div>
					</form>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary" onclick="$('#approve-form').submit();">Confirmar</button>
				</div>
			</div>
		</div>
	</div>


	<div class="modal" tabindex="-1" role="dialog" id="reject-docs-modal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Rechazar documentación</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<form action="{{ url('admin/instructores/'.$instructor->id.'/rechazar_doc') }}" method="POST" id="reject-docs-form">
						@csrf
						<div class="form-group">
							<label>Motivo del rechazo de documentación</label>
							<input type="text" class="form-control" name="reason">
							@if ($errors->doc_rejectal->has('reason'))
					        <span class="invalid-feedback" role="alert" style="display: block;">
					            <strong>{{ $errors->doc_rejectal->first('reason') }}</strong>
					        </span>
					    	@endif
						</div>
					</form>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary" onclick="$('#reject-docs-form').submit();">Confirmar</button>
				</div>
			</div>
		</div>
	</div>
	@endif

@endif



@endsection


@section('content')

	<!-- Breadcrumbs-->
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Dashboard</a></li>
			<li class="breadcrumb-item active">Lista de instructores</li>
			<li class="breadcrumb-item active">Detalles de instructor</li>
		</ol>

		@if($instructor != null)

		<div class="box_general padding_bottom">
			
			@if(!$instructor->isApproved() && $instructor->approvalDocsSent())
			<button class="btn btn-info btn-sm" data-toggle="modal" data-target="#approval-modal">Aprobar instructor</button>
			<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#reject-docs-modal">Rechazar documentación</button>
			@endif


		</div>

      	<div class="row">
      		<div class="col-md-6">
				<div class="box_general padding_bottom">
					<div class="header_box">
						<h2 class="d-inline-block">Datos personales y de la cuenta</h2>
					</div>
					<div class="list_general">
						
						<div class="row" style="margin-bottom: 20px">
							<div class="col-lg-2">
								<label><strong>ID</strong></label><br/>
								{{ $instructor->id }}
							</div>

							<div class="col-lg-5">
								<label><strong>Nombre y apellido</strong></label><br/>
								{{ $instructor->name.' '.$instructor->surname }}
							</div>

							<div class="col-lg-5">
								<label><strong>E-mail</strong></label><br/>
								{{ $instructor->email }}
							</div>
						</div>
						
						<div class="row" style="margin-bottom: 20px">
							<div class="col-lg-6">
								<label><strong>Nro tel</strong></label><br/>
								@if($instructor->phone_number)
									{{ $instructor->phone_number }}
								@else
									-
								@endif
							</div>
						</div>

						<div class="row" style="margin-bottom: 20px">
							<div class="col-lg-3">
								<label><strong>Aprobación</strong></label><br/>
								@if(!$instructor->isApproved())
									@if(!$instructor->approvalDocsSent())
										<span class="badge badge-dark">Envío doc. pendiente</span>
									@else
										<span class="badge badge-warning">Docs enviados. Revisar</span>
									@endif
								@else
									<span class="badge badge-success">Aprobado</span>
								@endif
								
							</div>

							<div class="col-lg-3">
								<label><strong>Fotos certif.</strong></label><br/>
								@if($instructor->approvalDocsSent())

									@foreach( explode(',', $instructor->professional_cert_imgs) as $img_path )
										<a href="{{ Storage::url($img_path) }}" target="_blank">Imágen</a><br/>
									@endforeach

								@else
									-
								@endif
								
							</div>

							<div class="col-lg-3">
								<label><strong>Fotos documento</strong></label><br/>
								@if($instructor->approvalDocsSent())

									@foreach( explode(',', $instructor->identification_imgs) as $img_path )
										<a href="{{ Storage::url($img_path) }}" target="_blank">Imágen</a><br/>
									@endforeach

								@else
									-
								@endif
							</div>

							<div class="col-lg-3">
								<label><strong>Fecha enviado</strong></label><br/>
								@if($instructor->approvalDocsSent())
									{{ date('d/m/Y H:i:s', strtotime($instructor->documents_sent_at)) }}
								@else
									-
								@endif
							</div>
						</div>

						<div class="row" style="margin-bottom: 20px">
							<div class="col-lg-3">
								<label><strong>Tipo documento</strong></label><br/>
								@if($instructor->isApproved())
									{{ $instructor->identification_type }}
								@else
									-
								@endif
							</div>

							<div class="col-lg-4">
								<label><strong>Nro. documento</strong></label><br/>
								@if($instructor->isApproved())
									{{ $instructor->identification_number }}
								@else
									-
								@endif
							</div>
						</div>

					</div>
				</div>

				<div class="box_general padding_bottom">
					<div class="header_box">
						<h2 class="d-inline-block">Balance</h2>
						<h2 style="float: right; color: #5292e6 !important;">${{ $instructor->balance }}</h2>
					</div>
					<div class="list_general">
						
						<h6>Transacciones</h6>
						<table class="table">
							<thead>
								<tr>
									<th>ID</th>
									<th>Fecha</th>
									<th>Concepto</th>
									<th>Monto</th>
									<th>Saldo</th>
								</tr>
							</thead>
						</table>
						
						
					</div>
				</div>

      		</div>

      		<div class="col-md-6">
				<div class="box_general padding_bottom">
					<div class="header_box">
						<h2 class="d-inline-block">Detalles del servicio</h2>
					</div>
					<div class="list_general">
						
						@if($instructor->isApproved())


						@else

						<div class="alert alert-warning">El instructor no tuvo su documentación verificada aún. No podrá ofrecer sus servicios hasta que la verifique.</div>
						@endif						
					</div>
				</div>
      		</div>

		</div>
		<!-- /box_general-->

		@else

		<h3>No se encontró el instructor</h3>

		@endif
		

@endsection



@section('custom-js')

@if(!$errors->approval->isEmpty())
<script>
$('#approval-modal').modal("show");
</script>
@endif

@endsection