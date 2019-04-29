@extends('admin.layouts.main')


@section('content')

	<!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item active">Lista de usuarios</li>
        <li class="breadcrumb-item active">Detalles de usuario</li>
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
								{{ $user->id }}
							</div>

							<div class="col-lg-5">
								<label><strong>Nombre y apellido</strong></label><br/>
								{{ $user->name.' '.$user->surname }}
							</div>

							<div class="col-lg-5">
								<label><strong>E-mail</strong></label><br/>
								{{ $user->email }}
							</div>
						</div>

					</div>
				</div>


      		</div>

      		<div class="col-md-6">
				<div class="box_general padding_bottom">
					<div class="header_box">
						<h2 class="d-inline-block">Reservas</h2>
					</div>
					<div class="list_general">
										
					</div>
				</div>
      		</div>

		</div>
		<!-- /box_general-->

@endsection


