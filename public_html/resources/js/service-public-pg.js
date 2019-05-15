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

		var init_date = getFirstAvailableDate();
		drp.setEndDate(init_date);
		drp.setStartDate(init_date);
	});







	// Estos 2 eventos sgtes. están registrados también en daterangepicker, pero estos se ejecutan después.
	drp.container.find('.calendar').on('click.daterangepicker', '.prev', function() { 
		updateDatePicker();
	});
	drp.container.find('.calendar').on('click.daterangepicker', '.next', function() { 
		updateDatePicker();
	});
	/*drp.element.on('keydown.daterangepicker', function() {
		//alert(1);
	});*/

	$("#date-picker-input").on("show.daterangepicker", function(ev, picker) {
		updateDatePicker();
	});
	$("body").on("mousedown", ".date-min-price", function() {
		$(this).parent().trigger("mousedown");
	});



	$('#date-picker-input').on('apply.daterangepicker', function(ev, picker) {

		var date = picker.startDate;

		$(this).val(date.format('DD/MM/YYYY'));

		var dayData = calendar[date.month()+1][date.date()];

		if(typeof dayData !== 'undefined')
		{
			if(dayData.available)
			{
				$("#price-per-block").text("$"+Math.round(dayData.ppb));

				for(var i=0; i<4; i++) {
					$("#hour-block-"+i).prop("disabled", !dayData["blocks_available"][i]);
				}
			}
		}
		

	});

	$('#date-picker-input').on('cancel.daterangepicker', function(ev, picker) {
		$(this).val('');
	});






	// Removes dates from datepicker belonging to other months, populates prices, and disables unavailable dates.
	function updateDatePicker()
	{
		trimExternalDates();
		addPricesAndDisableOccupied();
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

					$(td).html("").removeClass("available weekend off").addClass("date-deleted");	

					if((index == 6 && month == "previous") || (index == 0 && month == "next")) {
						$(tr).remove();
						return false; // break
					}

				}

			});

		});

	}


	function addPricesAndDisableOccupied()
	{

		var month = drp.leftCalendar.month.month() + 1;

		drp.container.find(".calendar.left table tbody td").each(function(index, elem) {

			if($(elem).hasClass("date-deleted"))
				return; // continue loop

			var day = parseInt(elem.innerText);

			if(typeof calendar[month][day] !== 'undefined') // some days at the beginning and end of the datepicker may not be in the array.
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


	function getFirstAvailableDate() {

		for(var monthIndex in calendar)
		{
			for(var dayIndex in calendar[monthIndex]) 
			{
				if(calendar[monthIndex][dayIndex].available)
					return dayIndex+"/"+monthIndex+"/"+moment().year();
			}
		}

	}



	function pickerStartDate()
	{
		if(moment() < moment(activity_start, DATE_FORMAT)) {
			return activity_start;
		}
		return moment().format(DATE_FORMAT);
	}

});


function kFormatter(num) {
    return Math.abs(num) > 999 ? Math.sign(num)*((Math.abs(num)/1000).toFixed(1)) + 'k' : Math.sign(num)*Math.abs(num)
}