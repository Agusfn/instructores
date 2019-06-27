Dropzone.autoDiscover = false;
var hourSlider = document.getElementById('hour_slider');


$(document).ready(function() {

	$('[data-toggle="tooltip"]').tooltip();

	var imgDropzone = new Dropzone("#img-dropzone", {
		paramName: "file",
		maxFilesize: 4,
		maxFiles: 5,
		addRemoveLinks: true,
		parallelUploads: 1,
		acceptedFiles: "image/jpeg,image/png",

		init: function() {
			var imgDropzone = this;
			uploaded_imgs.forEach(function(img) {
				var file = { 
	                status: Dropzone.ADDED,
	                name_in_server: img.name,
					imageUrl: img_dir + img.thumbnail_name,
					accepted: true,
				};
				imgDropzone.emit("addedfile", file);
				imgDropzone.emit("thumbnail", file, file.imageUrl);
				imgDropzone.emit("complete", file);
				imgDropzone.files.push(file);
			});
		}
	});

	imgDropzone.on("success", function(file, response) {
   		file.name_in_server = response.img.name;
   		console.log(file);
	    console.log(response);
	});

	imgDropzone.on("error", function(file, response) {
   		console.log(file);
	    console.log(response);
	});

	imgDropzone.on("removedfile", function(file) {
		console.log(file);
		if(file.accepted == true) {
			
			$.ajax({
				type: "POST",
				url: app_url + "instructor/panel/servicio/eliminar_imagen",
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    },

				data: { file_name: file.name_in_server },

				success: function(response) {
					console.log(response); // debug
				},

				error: function (jqXhr, textStatus, errorMessage) {
					if(jqXhr.status == 422)
						alert(jqXhr.responseText);
					else
						alert("An error ocurred with the request.");
			    }

			});
		}
		file.previewElement.remove();
	});

});





$(document).ready(function() {

	$('#allow-adults, #allow-kids, #instruct-ski, #instruct-snowboard, #separate-working-hours, #allow-group-classes').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-blue',
		increaseArea: '20%'
	});

	$("#allow-group-classes").on("ifChecked", function(event) {
		$("#max-group-size").parent().show();
		$("#group-discounts-table").show();
		$("#max-group-size").trigger("change");
	});

	$("#allow-group-classes").on("ifUnchecked", function(event) {
		$("#group-discounts-table").hide();
		$("#max-group-size").parent().hide();
	});

	$("#max-group-size").change(function() {

		$("#group-discounts-table tbody tr").hide();

		var max_persons = $(this).val();

		for(var i=2; i<=max_persons; i++) {
			$("#person"+i+"-discount").closest("tr").show();
		}
	});


	create_hour_slider(
		$("input[name=worktime_hour_start").val(), 
		$("input[name=worktime_hour_end").val(), 
		$("input[name=worktime_alt_hour_start").val(), 
		$("input[name=worktime_alt_hour_end").val()
	);


	$("#separate-working-hours").on("ifChecked", function(event) {
		hourSlider.noUiSlider.destroy();
		create_hour_slider(9, 11, 13, 17);
	});

	$("#separate-working-hours").on("ifUnchecked", function(event) {
		hourSlider.noUiSlider.destroy();
		create_hour_slider(9, 17);
	});


	$("#submit-form-btn").click(function() {

		// Validate
		if($("textarea[name=description]").val() == "") {
			alert("Ingresa una descripción.");
			return;
		}
		 
		if(!$("#instruct-ski").prop("checked") && !$("#instruct-snowboard").prop("checked")) {
			alert("Debes brindar clases de al menos una disciplina.");
			return;
		}

		if($("textarea[name=features]").val() == "") {
			alert("Ingresa alguna característica de las clases que ofreces.");
			return;
		}

		if(!$("#allow-adults").prop("checked") && !$("#allow-kids").prop("checked")) {
			alert("Selecciona si brindas clases a adultos, a niños, o a todos.");
			return;
		}

		for(var i=2; i<=6; i++) {
			if(!$.isNumeric($("#person" + i + "-discount").val())) {
				alert("Ingresa valores numéricos en los porcentajes de descuento.");
				return;
			}
		}


		$("#service-details").submit();
	});


});






$(document).ready(function() {
	
	$("#btn_submit_range").click(function() {

		if(!validate_range_form())
			return;

		var date_start = $("#date_start").val();
		var date_end = $("#date_end").val();
		var block_price = $("#block_price").val();

		$.ajax({

			type: "POST",

			url: app_url + "instructor/panel/servicio/agregar_fechas",

			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    },

			data: {
				date_start: date_start, 
				date_end: date_end, 
				block_price: block_price
			},

			beforeSend: function() {
				disable_range_form_btn($("#btn_submit_range"));
			},

			complete: function() {
				enable_range_form_btn($("#btn_submit_range"));
			},

			success: function(response) {
				console.log(response); // debug
				insert_date_range_row(date_start, date_end, block_price, response.range_id);
			},

			error: function (jqXhr, textStatus, errorMessage) {
				if(jqXhr.status == 422)
					alert(jqXhr.responseText);
				else
					alert("An error ocurred with the request.");
		    }

		});

	});



	$('body').on('click', '.delete-range-btn', function() {

		if(!confirm("¿Eliminar?"))
			return;

		var range_id = $(this).attr("data-range-id");
		var button = $(this);
		
		$.ajax({

			type: "POST",
			url: app_url + "instructor/panel/servicio/eliminar_fechas",
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    },
			data: { range_id: range_id },

			beforeSend: function() {
				disable_range_form_btn(button);
			},
			complete: function() {
				enable_range_form_btn(button);
			},
			success: function(response) {
				console.log(response); // debug
				button.closest("tr").remove();
			},
			error: function (jqXhr, textStatus, errorMessage) {
				if(jqXhr.status == 422)
					alert(jqXhr.responseText);
				else
					alert("An error ocurred with the request.");
		    }

		});

	});

});



function validate_range_form()
{
	
	var valid_date = /^\d{2}\/\d{2}\/\d{4}$/;

	if(!valid_date.test($("#date_start").val()) || !valid_date.test($("#date_end").val())) {
		alert("Ingresa fechas válidas.");
		return false;
	}


	//var date_start = new Date($("#date_start").val());
	var date_start = moment($("#date_start").val(), "DD/MM/YYYY");
	var date_end = moment($("#date_end").val(), "DD/MM/YYYY");
	var today = moment().hour(0).minute(0).second(0).millisecond(0);
	

	if(!date_start.isValid() || !date_end.isValid()) {
		alert("Ingresa fechas válidas.");
		return false;
	}

	if(date_end < date_start) {
		alert("La fecha 'hasta' debe ser antes o igual a la fecha 'desde'.");
		return false;
	}

	if(date_start < today) {
		alert("La fecha 'desde' no puede ser anterior o igual a hoy.");
		return false;
	}

	if(date_start.year() != date_end.year()) {
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




function disable_range_form_btn(button)
{
	button.prop("disabled", true);
	button.find("i").addClass("fa-spinner fa-spin");
}


function enable_range_form_btn(button)
{
	button.prop("disabled", false);
	button.find("i").removeClass("fa-spinner fa-spin");
}


function insert_date_range_row(date_start, date_end, block_price, range_id)
{
	var html = `
	<tr>
		<td>`+ date_start +`</td>
		<td>` + date_end + `</td>
		<td>$` + block_price + `</td>
		<td><button type="button" class="btn btn-danger btn-sm delete-range-btn" data-range-id="` + range_id + `"><i class="fa fa-times" aria-hidden="true"></i></button></td>
	</tr>`;

	$("#insert-form-row input").val("");

	$("#insert-form-row").before(html);
}



function create_hour_slider(start1, end1, start2 = null, end2 = null)
{
	
	var options = {
	    range: {
	        'min': 9,
	        'max': 17
	    },
	    step: 2,
	    margin: 2,
	    direction: 'ltr',
	    orientation: 'horizontal',
	    behaviour: 'tap-drag',
	    tooltips: true,
	    format: wNumb({
	        decimals: 0
	    }),
	    pips: {
	        mode: 'steps',
	        stepped: true,
	        density: 25
	    }
	};


	if(!start2 && !end2) {
		options.start = [start1, end1];
		options.connect = true;
	} 
	else {	
		options.start = [start1, end1, start2, end2];
		options.connect = [false, true, false, true, false];
	}

	noUiSlider.create(hourSlider, options);

	hourSlider.noUiSlider.on('update', function (values, handle) {

		if(values.length == 2) {
			$("input[name=worktime_hour_start]").val(values[0]);
			$("input[name=worktime_hour_end]").val(values[1]);
			$("input[name=worktime_alt_hour_start], input[name=worktime_alt_hour_end]").prop('disabled', true);
		}
		else if(values.length == 4) {
			$("input[name=worktime_hour_start]").val(values[0]);
			$("input[name=worktime_hour_end]").val(values[1]);
			$("input[name=worktime_alt_hour_start]").prop('disabled', false).val(values[2]);
			$("input[name=worktime_alt_hour_end]").prop('disabled', false).val(values[3]);
		}

	});

}