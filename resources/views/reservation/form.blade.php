@extends('layouts.main')



@section('content')

		<div class="hero_in cart_section">
			<div class="wrapper">
				<div class="container">
					<div class="bs-wizard clearfix">
						<div class="bs-wizard-step active">
							<div class="text-center bs-wizard-stepnum">Tu reserva</div>
							<div class="progress">
								<div class="progress-bar"></div>
							</div>
							<a href="#0" class="bs-wizard-dot"></a>
						</div>

						<div class="bs-wizard-step active">
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
							
							<form action="{{ url('reservar/'.$service->number.'/procesar') }}" method="POST" id="payment-form">
							@csrf
								<div class="form_title">
									<h3><strong>1</strong>Datos personales</h3>
									
								</div>
								<div class="step">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label>Nombre</label><br/>
												{{ $user->name }}
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label>Apellido</label><br/>
												{{ $user->surname }}
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label>Email</label><br/>
												{{ $user->email }}
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label>Teléfono</label>
												<input type="text" class="form-control" name="phone">
											</div>
										</div>
									</div>

								</div>
								<hr>
								<!--End step -->

								<div class="form_title">
									<h3><strong>2</strong>Información de pago</h3>
								
								</div>
								<div class="step">
									
									<div class="row">
										<div class="col-md-5">
											<div class="form-group">
												<label>Nombre completo titular</label>
												<input type="text" class="form-control" data-checkout="cardholderName">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Tipo documento</label>
												<select class="form-control" id="document-type" data-checkout="docType"></select>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Numero de documento</label>
												<input type="text" class="form-control" data-checkout="docNumber">
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6 col-sm-12">
											<div class="form-group">
												<label>Número de tarjeta</label>
												<input type="text" class="form-control" id="card_number" data-checkout="cardNumber" maxlength="16">
											</div>
										</div>
										<div class="col-md-6 col-sm-12">
											<img src="{{ asset('resources/img/cards_all.svg') }}" alt="Cards" class="cards-payment">
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Fecha de vencimiento</label>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<input type="text" class="form-control" placeholder="MM" data-checkout="cardExpirationMonth" maxlength="2" >
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<input type="text" class="form-control" placeholder="Year" data-checkout="cardExpirationYear" maxlength="4">
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Código de seguridad</label>
												<div class="row">
													<div class="col-4">
														<div class="form-group">
															<input type="text" class="form-control" placeholder="CCV" data-checkout="securityCode" maxlength="4">
														</div>
													</div>
													<div class="col-8">
														<img src="{{ asset('resources/img/icon_ccv.gif') }}" width="50" height="29" alt="ccv"><small>últimos 3 dígitos</small>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Cuotas</label>
												<select class="form-control" id="installments" name="installments"></select>
											</div>
										</div>
										<div class="col-md-6">
											<select id="issuer" name="issuer"></select>
										</div>

									</div>
									<!--End row -->

								</div>
								<hr>
								<!--End step -->


								<div class="form_title">
									<h3><strong>3</strong>Dirección de facturación</h3>
									
								</div>
								<div class="step">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label>País</label>
												<select class="form-control add_bottom_15" name="address_country" autocomplete="off">
													@foreach($countries as $code => $name)
													<option value="{{ $code }}" @if($code=='ARG') selected="" @endif>{{ $name }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label>Dirección</label>
												<input type="text" class="form-control" name="address">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6 col-sm-12">
											<div class="form-group">
												<label>Ciudad</label>
												<input type="text" name="address_city" class="form-control">
											</div>
										</div>
										<div class="col-md-3 col-sm-6">
											<div class="form-group">
												<label>Provincia</label>
												<input type="text" name="address_state" class="form-control">
											</div>
										</div>
										<div class="col-md-3 col-sm-6">
											<div class="form-group">
												<label>Código postal</label>
												<input type="text" name="address_postal_code" class="form-control">
											</div>
										</div>
									</div>
									<!--End row -->
								</div>

								<!-- Data of classes to make a reservation for -->
								<input type="hidden" name="date" value="{{ $quote->serviceDate->format('d/m/Y') }}">
								<input type="hidden" name="persons" value="{{ $quote->personAmmount }}">
								<input type="hidden" name="t_start" value="{{ $quote->blockStart }}">
								<input type="hidden" name="t_end" value="{{ $quote->blockEnd }}">
								<!-- Payment data -->
								<input type="hidden" id="amount" value="{{ $quote->total }}" />
								<input type="hidden" name="paymentMethodId" />
								<input type="hidden" name="card_token" />
							</form>

							<hr>
							<!--End step -->
							<div id="policy">
								<h5>Política de cancelación</h5>
								<p class="nomargin">Por favor lea <a href="#0">Nuestras política de cancelación</a>, antes de finalizar su compra.</p>
							</div>
							
						</div>
	
					</div>
					<!-- /col -->
					
					<aside class="col-lg-4" id="sidebar">
						<div class="box_detail">

							<div style="margin-bottom: 15px">
								<div class="row">
									<div class="col-8">Subtotal</div>
									<div class="col-4">${{ $quote->classesPrice }}</div>
								</div>
								<div class="row">
									<div class="col-8">Tarifa serv. pagos</div>
									<div class="col-4">${{ $quote->payProviderFee }}</div>
								</div>
								<div class="row" style="display: none">
									<div class="col-8">Costo financiación (<span id="installment-number"></span> cuotas)</div>
									<div class="col-4" id="interest-amt">$1234</div>
								</div>
							</div>

							<div id="total_cart">
								Total <span class="float-right" id="total">${{ $quote->total }}</span>
							</div>
							<ul class="cart_details">
								<li>Fecha <span>{{ $quote->serviceDate->format("d/m/Y") }}</span></li>
								<li>Horas <span>{{ App\Lib\Reservations::blocksToReadableHourRange($quote->blockStart, $quote->blockEnd) }}</span></li>
								<li>Personas <span>{{ $quote->personAmmount }}</span></li>
							</ul>
							<button type="button" class="btn_1 full-width purchase">Pagar</button>
							<div class="text-center"></div>
						</div>
					</aside>
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /bg_color_1 -->


@endsection


@section('custom-js')
<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
<script>
Mercadopago.setPublishableKey("{{ App\Lib\MercadoPago::getPublicKey() }}");
</script>
<script src="{{ asset('resources/js/reservation-form.js') }}"></script>
@endsection