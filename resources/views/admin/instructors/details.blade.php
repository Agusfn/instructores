@extends('admin.layouts.main')


@section('content')

	<!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item active">Lista de instructores</li>
        <li class="breadcrumb-item active">Detalles de instructor</li>
      </ol>
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
									{{ $instructor->professional_cert_imgs }}
								@else
									-
								@endif
								
							</div>

							<div class="col-lg-3">
								<label><strong>Fotos documento</strong></label><br/>
								@if($instructor->approvalDocsSent())
									{{ $instructor->identification_imgs }}
								@else
									-
								@endif
							</div>

							<div class="col-lg-3">
								<label><strong>Fecha enviado</strong></label><br/>
								@if($instructor->approvalDocsSent())
									{{ $instructor->documents_sent_at }}
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

@endsection


