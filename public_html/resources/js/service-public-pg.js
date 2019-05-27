var DATE_FORMAT = "DD/MM/YYYY";


$(document).ready(function() {


	$('#date-picker-input').daterangepicker({
		minDate: pickerStartDate(),
		maxDate: activity_end,
		autoUpdateInput: false,
		singleDatePicker: true,
		opens: 'left',
		locale: {
			format: DATE_FORMAT,
			cancelLabel: 'Clear'
		}
	});

	var drp = $('#date-picker-input').data('daterangepicker');


	var calendar;

	fetchCalendarData(function(calendar_resp) {
		calendar = calendar_resp;
	});

	var selected_date;
	var selected_blocks = [];
	var price_per_block;



	/******************** EVENTS *********************/
	/**************************************************/



	// Los 2 eventos sgtes. están registrados también en daterangepicker, pero estos se ejecutan después.
	drp.container.find('.calendar').on('click.daterangepicker', '.prev', function() { 
		updateDatePicker();
	});
	drp.container.find('.calendar').on('click.daterangepicker', '.next', function() { 
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

			updateTimeBlockButtons(dayData);
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
		
		if(selected_date == null) {
			$("#date-picker-input").trigger("click");
			return;
		}
		if(selected_blocks.length == 0) {
			alert("Selecciona los horarios");
			return;
		}
		$("#book-form").submit();
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

		drp.container.find(".calendar.left table tbody tr").each(function(index, tr) {

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

		var month = drp.leftCalendar.month.month() + 1;

		drp.container.find(".calendar.left table tbody td").each(function(index, elem) {

			if($(elem).hasClass("date-deleted"))
				return; // continue loop

			var day = parseInt(elem.innerText);


			if($(elem).hasClass("start-date") && selected_date == null)
				$(elem).removeClass("active start-date end-date");


			if(typeof calendar[month][day] !== 'undefined') // some dates may not exist in the array: they belong to other months, or outside the activity period, or the calendar didn't load yet
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



	function pickerStartDate()
	{
		if(moment() < moment(activity_start, DATE_FORMAT)) {
			return activity_start;
		}
		return moment().format(DATE_FORMAT);
	}


	/**
	 * Enable or disable time block buttons using the data from json calendar, with a given date.
	 */
	function updateTimeBlockButtons(dayData)
	{
		if(dayData.available) {
			for(var i=0; i<4; i++) {
				$("#hour-block-"+i).prop("disabled", !dayData["blocks_available"][i]);
			}
		}
	}


	/**
	 * De-selects the time block buttons that do not form a continuous range, when a new time block is selected.
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

		if(selected_blocks.length > 0)
		{
			
			var personAmmt = parseInt($("input[name=persons]").val());
			var subtotal = 0;

			for(var i=1; i<=personAmmt; i++) {

				var personTotal = price_per_block - (price_per_block * group_discounts[i]/100);
				personTotal = personTotal * selected_blocks.length;
				subtotal += personTotal;

				if(personAmmt > 1) {
					var person = i + "º persona";

					if(group_discounts[i] > 0)
						person += " ("+group_discounts[i]+"% off)";
				}
				else
					var person = "Clases instructor";
				
				$(".total-summary table tbody").append("<tr><td>"+person+"</td><td>$"+round(personTotal)+"</td></tr>");
			}

			var serviceCharge = getServiceCharge(subtotal);
			var total = subtotal + serviceCharge;

			$(".total-summary table tbody").append("<tr><td>Cargo de servicio</td><td>$"+round(serviceCharge)+"</td></tr>");
			$(".total-summary table tbody").append("<tr><td><strong>Total</strong></td><td><strong>$"+round(total)+"</strong></td></tr>");
			$("input[name=last_price]").val(round(total));
		}

	}



	function getServiceCharge(subtotal)
	{
		return subtotal * 0.15;
	}


});


function kFormatter(num) {
    return Math.abs(num) > 999 ? Math.sign(num)*((Math.abs(num)/1000).toFixed(1)) + 'k' : Math.sign(num)*Math.abs(num)
}


function round(num)
{
	return Math.round(num * 100) / 100;
}