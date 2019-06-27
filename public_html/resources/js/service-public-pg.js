var DATE_FORMAT = "DD/MM/YYYY";

var daterangepicker;

var calendar;
var selected_date;
var selected_blocks = [];
var price_per_block;



$(document).ready(function() {


	$('#date-picker-input').daterangepicker({
		minDate: start_date,
		maxDate: end_date,
		autoUpdateInput: false,
		singleDatePicker: true,
		opens: 'left',
		locale: {
			format: DATE_FORMAT,
			cancelLabel: 'Clear'
		}
	});

	daterangepicker = $('#date-picker-input').data('daterangepicker');

	fetchCalendarData(function(response) {
		calendar = response["calendar"];
		$("#price-per-block").text("$"+response["lowest_price"]);
	});


	/******************** EVENTS *********************/
	/**************************************************/


	$("#discipline-selection > div > button").click(function() {
		$("#discipline-selection > div > button").removeClass("discipline-selected");
		$(this).addClass("discipline-selected");
		$("input[name=discipline]").val($(this).data("discipline"));
	});


	// Los 2 eventos sgtes. están registrados también en daterangepicker, pero estos se ejecutan después.
	daterangepicker.container.find('.calendar').on('click.daterangepicker', '.prev', function() { 
		updateDatePicker();
	});
	daterangepicker.container.find('.calendar').on('click.daterangepicker', '.next', function() { 
		updateDatePicker();
	});
	$("#date-picker-input").on("show.daterangepicker", function(ev, picker) {
		updateDatePicker();
	});
	$("body").on("mousedown", ".date-min-price", function() {
		$(this).parent().trigger("mousedown");
	});



	$('#date-picker-input').on('apply.daterangepicker', function(ev, picker) {

		if($("#hour-selection:hidden"))
			$("#hour-selection").show();

		$("#hour-block-0, #hour-block-1, #hour-block-2, #hour-block-3").removeClass("hour-selected");
		selected_blocks = [];

		selected_date = picker.startDate;

		$(this).val(selected_date.format('DD/MM/YYYY'));

		var dayData = calendar[selected_date.month()+1][selected_date.date()];

		if(typeof dayData !== 'undefined')
		{
			price_per_block = dayData.ppb;

			updateTimeBlockButtons(selected_date, dayData);

			$("#price-from-label").hide();
			$("#price-per-block").text("$"+Math.round(price_per_block));

			updateTotalSummary();
		}
		
	});


	$('#date-picker-input').on('cancel.daterangepicker', function(ev, picker) {
		$(this).val('');
	});



	$("#hour-block-0, #hour-block-1, #hour-block-2, #hour-block-3").click(function() {

		if(!$(this).hasClass('hour-selected')) {

			var hourBlock = $(this).data("hour-block");
			checkInvalidRangeBlocksOnAdd(hourBlock);
			$(this).addClass("hour-selected");
			
		}
		else {
			$(this).removeClass("hour-selected");
			checkIvalidRangeBlocksOnRemove();
		}

		selected_blocks = getSelectedTimeBlocks();
		console.log(selected_blocks);

		if(selected_blocks.length >= 1) {
			$("input[name=t_start]").val(selected_blocks[0]);
			$("input[name=t_end]").val(selected_blocks[selected_blocks.length-1]);
		}

		updateTotalSummary();
	});


	$(".qtyButtons").on("click", ".qtyInc, .qtyDec", function() {
		updateTotalSummary();
	});



	$("#book-btn").click(function() {
		
		if($("input[name=discipline]").val() == "") {
			alert("Selecciona la disciplina deportiva");
			return;
		}
		if(selected_date == null) {
			$("#date-picker-input").trigger("click");
			return;
		}
		if(selected_blocks.length == 0) {
			alert("Selecciona los horarios");
			return;
		}
		if(selectedPeopleAmount() == 0) {
			alert("Selecciona al menos una persona para reservar la clase.");
			return;
		}
		if(selectedPeopleAmount() > max_group_size) {
			alert("La cantidad máxima de personas que pueden asistir a la clase es de " + max_group_size + ".");
			return;
		}
		$("#book-form").submit();
	});

});




/******************** FUNCTIONS *************************/
/********************************************************/



// Removes dates from datepicker belonging to other months, populates prices, and disables unavailable dates.
function updateDatePicker()
{
	trimExternalDates();
	addPricesAndDisableUnavailables();
}





// Removes the dates belonging to the previous and the next month from the date calendar, removing whole rows if these 
// contain only removed dates.
function trimExternalDates()
{
	var month = "previous";
	var dayOfMonth;

	daterangepicker.container.find(".calendar.left table tbody tr").each(function(index, tr) {

		$(tr).find("td").each(function(index, td) {

			dayOfMonth = parseInt(td.innerText);

			if(month == "previous" && dayOfMonth == 1)
				month = "current";
			else if(month == "current" && dayOfMonth == 1) 
				month = "next";

			if(month == "previous" || month == "next") {

				$(td).html("").removeClass("available weekend off active start-date end-date").addClass("date-deleted");	

				if((index == 6 && month == "previous") || (index == 0 && month == "next")) {
					$(tr).remove();
					return false; // break
				}

			}

		});

	});

}


/**
 * Del mes mostrado en el calendario, agrega precios a las fechas disponibles y desactiva los días no disponibles.
 * Se ejecuta cada vez que se abre o cambia de mes en el datepicker.
 */
function addPricesAndDisableUnavailables()
{

	var month = daterangepicker.leftCalendar.month.month() + 1;

	daterangepicker.container.find(".calendar.left table tbody td").each(function(index, elem) {

		if($(elem).hasClass("date-deleted"))
			return; // continue loop

		var day = parseInt(elem.innerText);


		// unmark initial selected date if no date has been chosen yet (datepicker doesn't support no initial date)
		if($(elem).hasClass("start-date") && selected_date == null) 
			$(elem).removeClass("active start-date end-date");


		// Ignore dates previous than today
		var cellDate = moment().month(month-1).date(day).startOf("day");
		if(cellDate.isBefore(moment().startOf("day"))) {
			return;
		}


		if(typeof calendar[month][day] !== 'undefined') // some dates in the <td> may not exist in the array: they belong to other months, or outside the activity period, or the calendar didn't load yet
		{
			if(calendar[month][day].available == true) {
				$(elem).html(day + "<div class='date-min-price'>$" + kFormatter(calendar[month][day]["ppb"]) + "</div>");
			} 
			else {
				$(elem).removeClass("available").addClass("off disabled")
				.html(day + "<div class='date-min-price' style='visibility:hidden'>.</div>");
			}

		}

	});

}

/**
 * Obtain with AJAX the instructor's availability and price calendar.
 */
function fetchCalendarData(callback)
{
	$.ajax({

		type: "POST",
		url: app_url + "instructor/"+serv_number+"/calendar",
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },

		success: function(response) {
			console.log(response); // debug
			callback(response);
		},

		error: function (jqXhr, textStatus, errorMessage) {
			alert("An error ocurred requesting the availability calendar.");
	    }
	});

}


/**
 * Enable or disable time block buttons using the data from json calendar, with a given date.
 */
function updateTimeBlockButtons(date, dayData)
{
	
	
	if(dayData.available) {

		// If it's today. Disable classes that start in 2 hours or less even if they are available.
		if(date.isSame(moment().startOf("day")))
		{
			for(var i=0; i<4; i++) {

				if(!dayData["block_availability"][i]) {
					$("#hour-block-"+i).prop("disabled", true);
				} else {

					var classStartHour = 9 + (i*2);

					if(classStartHour - moment().hour() >= 2)
						$("#hour-block-"+i).prop("disabled", false);
					else
						$("#hour-block-"+i).prop("disabled", true);

				}
				
			}
		}
		else {
			for(var i=0; i<4; i++) {
				$("#hour-block-"+i).prop("disabled", !dayData["block_availability"][i]);
			}
		}

	}
	

}


/**
 * De-selects the time block buttons that do not form a continuous range, called when a new time block is selected.
 */
function checkInvalidRangeBlocksOnAdd(newBlock)
{
	var lastTrueStartBlock = null;

	for(var i=0; i<4; i++) {

		if(i == newBlock)
			break;

		if($("#hour-block-"+i).hasClass("hour-selected")) {
			if(lastTrueStartBlock == null)
				lastTrueStartBlock = i;
		}
		else
			lastTrueStartBlock = null;
	}

	if(lastTrueStartBlock == null)
		lastTrueStartBlock = newBlock;

	for(var i=0; i<4; i++) {

		if(i >= lastTrueStartBlock && i <= newBlock)
			continue;

		$("#hour-block-"+i).removeClass("hour-selected");
	}


}

/**
 * Checks the hour (or block) range when a block is de-selected, to see if it mantains the continuity. 
 * If not, every block is de-selected.
 */
function checkIvalidRangeBlocksOnRemove()
{
	var separateRangeFound = false;
	var lastSelected = null;

	for(var i=0; i<4; i++) {
		
		if($("#hour-block-"+i).hasClass("hour-selected")) {
			if(!separateRangeFound)
				lastSelected = i;
			else {
				$("#hour-block-0, #hour-block-1, #hour-block-2, #hour-block-3").removeClass("hour-selected");
				break;
			}
		}
		else {
			if(lastSelected != null)
				separateRangeFound = true;
		}
	}
}


/**
 * Gets array of selected time blocks numbers. Eg: [0,1,2]
 */
function getSelectedTimeBlocks()
{
	var blocks = [];
	for(var i=0; i<4; i++) {
		if($("#hour-block-"+i).hasClass("hour-selected"))
			blocks.push(i);
	}

	return blocks;
}



/**
 * The price per person is calculated, considering the discounts for groups assigned by the instructor, and then the total price is calculated.
 * Also, the price breakdown table is updated.
 */
function updateTotalSummary()
{
	$(".total-summary table tbody").empty();

	var personAmt = selectedPeopleAmount();

	if(selected_blocks.length > 0 && personAmt >= 1)
	{
		
		var classesPrice = 0;
		var subtotalPerPerson = price_per_block * selected_blocks.length;

		for(var i=1; i <= personAmt; i++) 
		{
			if(typeof group_discounts[i] === "undefined")
				group_discounts[i] = 0;

			var personTotal = subtotalPerPerson - (subtotalPerPerson * group_discounts[i]/100);
			classesPrice += personTotal;


			if(personAmt > 1) {
				var person = i + "º persona";
				if(group_discounts[i] > 0)
					person += " ("+group_discounts[i]+"% off)";
			}
			else
				var person = "Clases instructor";
			
			$(".total-summary table tbody").append("<tr><td>"+person+"</td><td>$"+round(personTotal)+"</td></tr>");
		}

		var mpFees = calculateMPFees(classesPrice);
		var total = classesPrice + mpFees;

		$(".total-summary table tbody").append("<tr><td>Tarifa serv. pagos</td><td>$"+round(mpFees)+"</td></tr>");
		$(".total-summary table tbody").append("<tr><td><strong>Total</strong></td><td><strong>$"+round(total)+"</strong></td></tr>");
		$("input[name=last_price]").val(round(total));
	}

}



/**
 * Calculates the necessary fee that should be added to the provided ammount, to recieve in net that same provided ammount.
 */
function calculateMPFees(to_recieve)
{
	return round(to_recieve * (1/(1 - 0.066429) - 1));
}


function getServiceCharge(subtotal)
{
	return subtotal * 0.15;
}



function selectedPeopleAmount()
{
	var count = 0;
	if($("input[name=adults_amount]").val() != undefined) {
		count += parseInt($("input[name=adults_amount]").val());
	}
	if($("input[name=kids_amount]").val() != undefined) {
		count += parseInt($("input[name=kids_amount]").val());
	}
	return count;
}



function kFormatter(num) {
    return Math.abs(num) > 999 ? Math.sign(num)*((Math.abs(num)/1000).toFixed(1)) + 'k' : Math.sign(num)*Math.abs(num)
}


function round(num)
{
	return Math.round(num * 100) / 100;
}
