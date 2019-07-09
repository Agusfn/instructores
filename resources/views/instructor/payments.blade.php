@extends('layouts.main')

@section('title', 'Mis pagos')




@section('content')
	<style type="text/css">

   html main {
   	overflow-y: hidden;

   }

	.profile-pic {
		width: 150px;
		height: 150px;
		border-top-left-radius: 50% 50%;
		border-top-right-radius: 50% 50%;
		border-bottom-right-radius: 50% 50%;
		border-bottom-left-radius: 50% 50%;
	}

 .sr {background-color: whitesmoke;}.
    #page {background-color: whitesmoke;}
    .mm-slideout { 
        background-color: #299aea!important;
        color: white !important;

    }
    .margin_80_55 {
        background-color: whitesmoke !important;

    }
    
    #registbotton{
        margin-top: 0%;
        margin-bottom: 0%;
        
       
    }

    #ofertas {
        display: none;
    }

    .main_title_3 span em {
    width: 60px;
    height: 2px;
    background-color: #0054a6!important;
    display: block;
}
    .mm-slideout {
        border-bottom: 1px solid #ededed!important;
   
    color: black !important;
}
   .mm-slideout p{
    
    color: black !important;
}
 .mm-slideout   ul > li span > a {
    color: white !important;   
}

.mm-slideout   ul > li span > a:hover {
    color: #fc5b62 !important;   
}

.hamburger-inner, .hamburger-inner::after, .hamburger-inner::before {
    width: 30px;
    height: 4px;
    background-color: #333 !important;
    border-radius: 0;
    position: absolute;
    transition-property: transform;
    transition-duration: .15s;
    transition-timing-function: ease;
}

#logo p {
    margin-top: -10px;
    font-size: medium;
    color: white!important;
}


</style>


<br><br>
        <div class="container margin_80_55"></div>


	
	<div class="container">
		


		 <div class="row">

                <aside class="col-lg-3" id="sidebar">
                       
                        @include('instructor.panel-nav-layout')
                </aside>
                <!--/aside -->

			<div class="col-md-9">

				<h4 class="add_bottom_30">
					Mis cobros
					<!--<div style="float:right; color: #2e79b9">$14610.50</div>-->
				</h4>

				@include('layouts.errors')

				@if(\Session::has("mp_assoc_success"))
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					Cuenta asociada exitosamente.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				@endif

				@if($mpAccount == null || $mpAccount->access_token == null)

				<p>Asociá tu cuenta de MercadoPago con la cual vas a recibir los pagos del servicio que vas a ofrecer. Deberás iniciar sesión con tu cuenta de MercadoPago o registrar una si no tenés.</p>
				<form action="{{ url('instructor/panel/cobros/url_asoc_mp') }}" method="POST">
					@csrf
					<button class="btn btn-success">Asociar cuenta de MercadoPago</button>
				</form>
				@else
				<table class="table">

					<thead>
						<tr>
							<th>Fecha</th>
							<th>Concepto</th>
							<th>Reserva</th>
							<th>Monto</th>
							<th>Saldo</th>
						</tr>
					</thead>
					<tbody>
						@foreach($balanceMovements as $movement)
						<tr>
							<td>{{ date('d/m/Y', strtotime($movement->date)) }}</td>
							<td>
								@if($movement->motive == App\InstructorBalanceMovement::MOTIVE_RESERVATION_PAYMENT)
								Pago de reserva
								@elseif($movement->motive == App\InstructorBalanceMovement::MOTIVE_COLLECTION)
								Retiro de dinero
								@endif
							</td>
							<td>
								@if($movement->motive == App\InstructorBalanceMovement::MOTIVE_RESERVATION_PAYMENT)
								<a href="">{{ $movement->reservation->code }}</a>
								@endif
							</td>
							<td>${{ floatval($movement->net_ammount) }}</td>
							<td></td>
						</tr>
						@endforeach
					</tbody>
				</table>

				@endif




			</div>

		</div>


	</div>
            
@endsection


