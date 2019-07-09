@extends('layouts.main')


@section('title', 'Reservas')




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
				
				<div class="col-lg-9">
				
				@if($instructor->isApproved())

				<h4 class="add_bottom_30">Reservas</h4>

				<table class="table">
					<thead>
						<tr>
							<th></th>
							<th>Fecha</th>
							<th>Código</th>
							<th>Estado</th>
							<th>Cliente</th>
							<th>Fecha clase</th>
							<th>Pers.</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						@if($reservations->count() == 0)
							<tr><td colspan="8" style="text-align: center;">No tienes reservas</td></tr>
						@else
							@foreach($reservations as $reservation)
							<tr>
								<td><a href="{{ url('instructor/panel/reservas/'.$reservation->code) }}"><i class="fa fa-search" aria-hidden="true"></i></a></td>
								<td>{{ $reservation->created_at->format('d/m/Y') }}</td>
								<td>{{ $reservation->code }}</td>
								<td>
									@if($reservation->isPaymentPending())
									<span class="badge badge-secondary">Pago pendiente</span>
									@elseif($reservation->isPendingConfirmation())
									<span class="badge badge-primary">Pagada - Confirmar</span>
									@elseif($reservation->isFailed())
									<span class="badge badge-danger">Pago fallido</span>
									@elseif($reservation->isRejected())
									<span class="badge badge-danger">Rechazada por instructor</span>
									@elseif($reservation->isConfirmed())
									<span class="badge badge-success">Confirmada</span>
									@elseif($reservation->isConcluded())
									<span class="badge badge-success">Concluída</span>
									@elseif($reservation->isCanceled())
									<span class="badge badge-danger">Cancelada</span>
									@endif
								</td>
								<td>{{ $reservation->user->name.' '.$reservation->user->surname[0].'.' }}</td>
								<td>{{ $reservation->reserved_class_date->format('d/m') }}&nbsp;&nbsp;&nbsp;{{ $reservation->readableHourRange(true) }}</td>
								<td>{{ $reservation->personAmount() }}</td>
								<td>${{ floatval($reservation->final_price) }}</td>
							</tr>
							@endforeach
						@endif
					</tbody>
				</table>

				{{ $reservations->links() }}
				@else
				<div class="alert alert-warning">
					Tu cuenta no ha sido aprobada aún. Para empezar a ofrecer tus servicios debés verificar tu documentación de identidad y certificación.
				</div>
				@endif

			</div>

		</div>


	</div>
            
@endsection
