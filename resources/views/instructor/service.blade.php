@extends('layouts.main')


@section('custom-css')
<link href="{{ asset('resources/vendor/dropzone/min/dropzone.min.css') }}" rel="stylesheet">
@endsection




@section('content')
	
	<section class="hero_in general start_bg_zoom"></section>
	<div class="container margin_60">
		


		<div class="row">

			@include('instructor.panel-nav-layout')

			<div class="col-9">

				@if(Auth::user()->isApproved())

				<h5 style="margin-bottom: 20px">Información del servicio</h5>

				<div class="form-group">
					<label>Título del servicio/clases brindadas</label>
					<input type="text" class="form-control" placeholder="Ej: Clases de Snowboard para todas las edades">
				</div>
				<div class="form-group">
					<label>Descripción</label>
					<textarea class="form-control"></textarea>
				</div>

				<div class="form-group">
					<label>Imágenes de presentación</label>
					<form action="/file-upload" class="dropzone" id="my-awesome-dropzone"></form>
				</div>

				<form>
					<h5 style="margin: 30px 0">Disponibilidad y precios</h5>


					<table class="table table-sm">
						<thead>
							<tr>
								<th>Desde</th>
								<th>Hasta</th>
								<th>Precio bloque 2hs</th>
								<th></th>
							</tr>
						</thead>

						<tbody>
							@foreach($service->dateRanges()->orderBy('date_start', 'ASC')->get() as $dateRange)
							<tr>
								<td>{{ $dateRange->date_start }}</td>
								<td>{{ $dateRange->date_end }}</td>
								<td>{{ $dateRange->price_per_block }}</td>
								<td><button type="button" class="btn btn-danger btn-sm delete-range-btn" data-range-id="{{ $dateRange->id }}"><i class="fa fa-times" aria-hidden="true"></i></button></td>
							</tr>
							@endforeach
							<tr id="insert-form-row">
								<td><input type="text" class="form-control" id="date_start"></td>
								<td><input type="text" class="form-control" id="date_end"></td>
								<td><input type="text" class="form-control" id="block_price"></td>
								<td><button type="button" class="btn btn-success btn-sm" id="btn_submit_range"><i class="fa fa-plus" aria-hidden="true"></i></button></td>
							</tr>
						</tbody>
					</table>

					<div class="form-group">

					</div>


					<button type="submit" class="btn btn-primary">Publicar servicio</button>
					

				</form>

				@else
				<div class="alert alert-warning">
					Tu cuenta no ha sido aprobada aún. Para empezar a ofrecer tus servicios debés verificar tu documentación de identidad y certificación.
				</div>
				@endif
			
			</div>

		</div>


	</div>
            
@endsection


@section('custom-js')
<script src="{{ asset('resources/vendor/dropzone/min/dropzone.min.js') }}"></script>


<script>




$(document).ready(function() {
	
	
	$("#btn_submit_range").click(function() {

		if(!validate_range_form())
			return;

		var date_start = $("#date_start").val();
		var date_end = $("#date_end").val();
		var block_price = $("#block_price").val();

		$.ajax({

			type: "POST",

			url: "{{ url('instructor/panel/servicio/agregar_fechas') }}",

			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    },

			data: {
				date_start: date_start, 
				date_end: date_end, 
				block_price: block_price
			},

			beforeSend: function() {
				disable_range_form_btn();
			},

			complete: function() {
				enable_range_form_btn();
			},

			success: function(response) {
				console.log(response); // debug

				if(response.success) {
					insert_date_range_row(date_start, date_end, block_price, response.range_id);
				} else {
					alert(response.error_msg);
				}
				
			},

			error: function (jqXhr, textStatus, errorMessage) {
		       console.log(errorMessage);
		    }

		});

	});


	$(".delete-range-btn").click(function() {

		var range_id = $(this).attr("data-range-id");

		alert(range_id);

		
		$.ajax({

			type: "POST",

			url: "{{ url('instructor/panel/servicio/eliminar_fechas') }}",

			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    },

			data: {
				date_start: date_start, 
				date_end: date_end, 
				block_price: block_price
			},

			beforeSend: function() {
				disable_range_form_btn();
			},

			complete: function() {
				enable_range_form_btn();
			},

			success: function(response) {
				console.log(response); // debug

				if(response.success) {
					insert_date_range_row(date_start, date_end, block_price, response.range_id);
				} else {
					alert(response.error_msg);
				}
				
			},

			error: function (jqXhr, textStatus, errorMessage) {
		       console.log(errorMessage);
		    }

		});




	});


});



function validate_range_form()
{
	
	var valid_date = /^\d{4}-\d{2}-\d{2}$/;

	if(!valid_date.test($("#date_start").val()) || !valid_date.test($("#date_end").val())) {
		alert("Ingresa fechas válidas.");
		return false;
	}


	var date_start = new Date($("#date_start").val());
	var date_end = new Date($("#date_end").val());
	var today = new Date();
	today.setHours(0,0,0,0);

	if(isNaN(date_start.getTime()) || isNaN(date_end.getTime())) {
		alert("Ingresa fechas válidas.");
		return false;
	}

	if(date_end < date_start) {
		alert("La fecha 'hasta' debe ser antes o igual a la fecha 'desde'.");
		return false;
	}

	if(date_start < today) {
		alert("La fecha 'desde' no puede ser anterior a hoy.");
		return false;
	}

	if(date_start.getYear() != date_end.getYear()) {
		alert("Ambas fechas deben ser del mismo año.");
		return false;
	}


	var block_price = $("#block_price").val();
	if(block_price == "" || isNaN(block_price)) {
		alert("Ingresa un precio numérico válido.");
		return false;
	}

	return true;
}



function disable_range_form_btn()
{
	$("#btn_submit_range").prop("disabled", true);
	$("#btn_submit_range i").removeClass("fa-plus");
	$("#btn_submit_range i").addClass("fa-spinner fa-spin");
}


function enable_range_form_btn()
{
	$("#btn_submit_range").prop("disabled", false);
	$("#btn_submit_range i").addClass("fa-plus");
	$("#btn_submit_range i").removeClass("fa-spinner fa-spin");
}


function insert_date_range_row(date_start, date_end, block_price, range_id)
{
	var html = `
	<tr>
		<td>`+ date_start +`</td>
		<td>` + date_end + `</td>
		<td>` + block_price + `</td>
		<td><button type="button" class="btn btn-danger btn-sm delete-range-btn" data-range-id="` + range_id + `"><i class="fa fa-times" aria-hidden="true"></i></button></td>
	</tr>`;

	$("#insert-form-row input").val("");

	$("#insert-form-row").before(html);
}

</script>

@endsection