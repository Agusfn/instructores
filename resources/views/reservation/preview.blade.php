@extends('layouts.main')

@section('title', 'Reservar clases')

<style type="text/css">

.hero_in {
    margin-top: 17px;
}
.mm-slideout {
    background-color: #0054a6!important;
   
}

header.header.sticky #logo p {
    color: #f8f9fa!important;
}

header.sticky .hamburger-inner, header.sticky .hamburger-inner::before, header.sticky .hamburger-inner::after {
    background-color: #f8f9fa!important;
}

</style>

@section('content')
<br><br><br>

		<div class="hero_in cart_section">
			<div class="">
				<div class="container">
					<div class="bs-wizard clearfix">
						<div class="bs-wizard-step active">
							<div class="text-center bs-wizard-stepnum">Tu reserva</div>
							<div class="progress">
								<div class="progress-bar"></div>
							</div>
							<a href="#0" class="bs-wizard-dot"></a>
						</div>

						<div class="bs-wizard-step disabled">
							<div class="text-center bs-wizard-stepnum">Pago</div>
							<div class="progress">
								<div class="progress-bar"></div>
							</div>
							<a href="#0" class="bs-wizard-dot"></a>
						</div>

						<div class="bs-wizard-step disabled">
							<div class="text-center bs-wizard-stepnum">Listo</div>
							<div class="progress">
								<div class="progress-bar"></div>
							</div>
							<a href="#0" class="bs-wizard-dot"></a>
						</div>
					</div>
					<!-- End bs-wizard -->
				</div>
			</div>
		</div>
		<!--/hero_in-->

		<div class="bg_color_1">
			<div class="container margin_60_35">
				<div class="row">
					<div class="col-lg-8">
						<div class="box_cart">
						<table class="table table-striped cart-list">
							<thead>
								<tr>
									<th>
										Item
									</th>
									<th>
										Descuento
									</th>
									<th>
										Precio
									</th>
									
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<div class="thumb_cart">
											<img src="{{ $service->instructor->getProfilePicUrl() }}" alt="$service->instructor->name">
										</div>
										<span class="item_cart">
											Clases de {{ ucfirst($quote->discipline) }} por
											{{ $quote->timeBlocksAmt * \App\Lib\Reservations::RESERVATION_BLOCK_LENGTH }}hs para
											{{ $quote->personAmount }} @if($quote->personAmount>1)personas @else persona @endif
										</span>
									</td>
									<td>
										{{-- round(100 - ($quote->classesPrice * 100 / ($quote->personAmount * $quote->pricePerBlock * $quote->timeBlocksAmt)), 1) --}}
										0%
									</td>
									<td>
										<strong>${{ $quote->classesPrice }}</strong>
									</td>
									<td class="options" style="width:5%; text-align:center;">
										{{--<a href="#"><i class="icon-trash"></i></a>--}}
									</td>
								</tr>
								
							</tbody>
						</table>
						{{--<div class="cart-options clearfix">
							<div class="float-left">
								<div class="apply-coupon">
									<div class="form-group">
										<input type="text" name="coupon-code" value="" placeholder="Tu cupón de descuento" class="form-control">
									</div>
									<div class="form-group">
										<button type="button" class="btn_1 outline">Aplicar cupón</button>
									</div>
								</div>
							</div>
							
						</div>
						<!-- /cart-options -->--}}
					</div>
					</div>
					<!-- /col -->
					
					<aside class="col-lg-4" id="sidebar">
						<div class="box_detail">
							<div style="margin-bottom: 10px">
								Subtotal <span class="float-right">${{ $quote->classesPrice }}</span><br/>
								Tarifa serv. pagos <span class="float-right">${{ $quote->payProviderFee }}</span>
							</div>
							<div id="total_cart">
								Total <span class="float-right">${{ $quote->total }}</span>
							</div>
							<ul class="cart_details">
								<li>Fecha <span>{{ $quote->serviceDate->format("d/m/Y") }}</span></li>
								<li>Horas <span>{{ App\Lib\Reservations::blocksToReadableHourRange($quote->blockStart, $quote->blockEnd) }}</span></li>
								<li>Personas <span>{{ $quote->personAmount }}</span></li>
							</ul>
							<form action="{{ url('reservar/'.$service->number.'/confirmar') }}" method="POST">
								@csrf
								<input type="hidden" name="discipline" value="{{ $quote->discipline }}" autocomplete="off">
								<input type="hidden" name="date" value="{{ $quote->serviceDate->format('d/m/Y') }}" autocomplete="off">
								<input type="hidden" name="adults_amount" value="{{ $quote->adultsAmount }}" autocomplete="off">
								<input type="hidden" name="kids_amount" value="{{ $quote->kidsAmount }}" autocomplete="off">
								<input type="hidden" name="t_start" value="{{ $quote->blockStart }}" autocomplete="off">
								<input type="hidden" name="t_end" value="{{ $quote->blockEnd }}" autocomplete="off">
								<button class="btn_1 full-width purchase">Checkout</button>
							</form>
							<div class="text-center"><small>No se carga dinero en esta etapa</small></div>
						</div>
					</aside>
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /bg_color_1 -->
@endsection