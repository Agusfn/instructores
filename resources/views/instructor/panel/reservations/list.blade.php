@extends('instructor.panel.layouts.main-layout')


@section('title', 'Reservas')


@section('custom-css')
	<style type="text/css">


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
    background-color: white!important;
    border-radius: 0;
    position: absolute;
    transition-property: transform;
    transition-duration: .15s;
    transition-timing-function: ease;
}




.reservation-table  .row.head > div {
	font-weight: bold
}

.reservation-table .row {
	padding: 13px 0;
	margin: 0;
	border-bottom: 1px solid #DDD;
}

.reservation-table > a > .row {
	color: #505050;
}

.reservation-table > a:not(:first-child) > .row:hover {
	background-color: #F0F0F0;
}

.reservation-table .row > div {
	padding-top: 3px;
	padding-bottom: 3px;
}


.space {
	width: 20px; 
	height: 1px
}
</style>
@endsection


@section('panel-tab-content')

			<div class="card">
				<div class="card-body">
					@if($instructor->isApproved())

					<h4 class="add_bottom_30">Reservas</h4>

					<div class="reservation-table add_bottom_30">
						<div class="row head d-none d-md-flex">
							<div class="col-md-2">Código</div>
							<div class="col-md-3">Estado</div>
							<div class="col-md-2">Cliente</div>
							<div class="col-md-2">Fecha clase</div>
							<div class="col-md-1">Pers.</div>
							<div class="col-md-2">Total</div>
						</div>

						@if($reservations->count() == 0)
							<div class="row" style="text-align: center;">No tienes reservas</div>
						@else
							@foreach($reservations as $reservation)

							<a href="{{ route('instructor.reservation', $reservation->code) }}">
							<div class="row">
								{{--<div class="col-md-1">
									<a href="{{ url('panel/reservas/'.$reservation->code) }}">
										<i class="fa fa-search" aria-hidden="true"></i>
									</a>
								</div>--}}
								<div class="col-md-2">
									<div class="float-left d-md-none space"></div>
									<div class="float-left d-md-none"><label>Código</label></div>
									<div class="float-right d-md-none space"></div>
									<div class="float-right float-md-none">{{ $reservation->code }}</div>
								</div>
								<div class="col-md-3">
									<div class="float-left d-md-none space"></div>
									<div class="float-left d-md-none"><label>Estado</label></div>
									<div class="float-right d-md-none space"></div>	
									<div class="float-right float-md-none">
										@if($reservation->isPaymentPending())
										<span class="badge badge-secondary">Pago pendiente</span>
										@elseif($reservation->isPendingConfirmation())
										<span class="badge badge-primary">Pagada - confirmar</span>
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
									</div>
								</div>
								<div class="col-md-2">
									<div class="float-left d-md-none space"></div>
									<div class="float-left d-md-none"><label>Cliente</label></div>
									<div class="float-right d-md-none space"></div>
									<div class="float-right float-md-none">
										{{ $reservation->user->name.' '.$reservation->user->surname[0].'.' }}
									</div>
								</div>
								<div class="col-md-2">
									<div class="float-left d-md-none space"></div>
									<div class="float-left d-md-none"><label>Fecha clase</label></div>
									<div class="float-right d-md-none space"></div>
									<div class="float-right float-md-none">
										{{ $reservation->reserved_class_date->day.' '.$reservation->reserved_class_date->shortMonthName }}, {{ $reservation->readableHourRange(true) }}
									</div>
								</div>
								<div class="col-md-1">
									<div class="float-left d-md-none space"></div>
									<div class="float-left d-md-none"><label>Personas</label></div>
									<div class="float-right d-md-none space"></div>
									<div class="float-right float-md-none">
										{{ $reservation->personAmount() }}
									</div>
								</div>
								<div class="col-md-2">
									<div class="float-left d-md-none space"></div>
									<div class="float-left d-md-none"><label>Total</label></div>
									<div class="float-right d-md-none space"></div>
									<div class="float-right float-md-none">
										${{ floatval($reservation->final_price) }}
									</div>
								</div>
							</div>
							</a>
							@endforeach
						@endif
					</div>

					{{ $reservations->links() }}

					@endif
				</div>
			</div>

@endsection
