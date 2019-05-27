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
							
							<form>

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
												<input type="text" id="telephone_booking" name="telephone_booking" class="form-control">
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
												<input type="text" class="form-control" id="name_card_bookign" name="name_card_bookign">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Tipo documento</label>
												<input type="text" class="form-control" id="name_card_bookign" name="name_card_bookign">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Numero de documento</label>
												<input type="text" class="form-control" id="name_card_bookign" name="name_card_bookign">
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6 col-sm-12">
											<div class="form-group">
												<label>Número de tarjeta</label>
												<input type="text" id="card_number" name="card_number" class="form-control">
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
														<input type="text" id="expire_month" name="expire_month" class="form-control" placeholder="MM">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<input type="text" id="expire_year" name="expire_year" class="form-control" placeholder="Year">
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
															<input type="text" id="ccv" name="ccv" class="form-control" placeholder="CCV">
														</div>
													</div>
													<div class="col-8">
														<img src="{{ asset('resources/img/icon_ccv.gif') }}" width="50" height="29" alt="ccv"><small>últimos 3 dígitos</small>
													</div>
												</div>
											</div>
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
												
												<div class="custom-select-form">
												<select class="wide add_bottom_15" name="country" id="country">
													<option value="" selected>País</option>
													<option value="Europe">Argentina</option>
													<option value="United states">Brasil</option>
													<option value="South America">Chile</option>
													<option value="Oceania">Colombia</option>
													<option value="Asia">Uruguay</option>
												</select>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label>Dirección</label>
												<input type="text" id="street_1" name="street_1" class="form-control">
											</div>
										</div>
										
									</div>
									<div class="row">
										<div class="col-md-6 col-sm-12">
											<div class="form-group">
												<label>Ciudad</label>
												<input type="text" id="city_booking" name="city_booking" class="form-control">
											</div>
										</div>
										<div class="col-md-3 col-sm-6">
											<div class="form-group">
												<label>Provincia</label>
												<input type="text" id="state_booking" name="state_booking" class="form-control">
											</div>
										</div>
										<div class="col-md-3 col-sm-6">
											<div class="form-group">
												<label>Código postal</label>
												<input type="text" id="postal_code" name="postal_code" class="form-control">
											</div>
										</div>
									</div>
									<!--End row -->
								</div>

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
								<li>Personas <span>{{ $quote->personAmmount }}</span></li>
							</ul>
							<a href="cart-3.html" class="btn_1 full-width purchase">Pagar</a>
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
@endsection