var DATE_FORMAT = "DD/MM/YYYY";


$(document).ready(function() {

	$('#date-picker-input').daterangepicker({
		minDate: '15/06/2019', // limite inferior, deberia ser cuando inicia la temporada del año corriente, o el dia de la fecha si ya inició
		maxDate: '15/09/2019', // limite superior, deberia ser cuando termina la temporada
		autoUpdateInput: false,
		singleDatePicker: true,
		opens: 'left',
		locale: {
			format: DATE_FORMAT,
			cancelLabel: 'Clear'
		}
	});
	var drp = $('#date-picker-input').data('daterangepicker');


	// Obtenemos por ajax los días no disponibles
	var unavailableDates = {
		5: [15,20,26,27,28], // 0: enero, 1: febrero... 11: diciembre
		6: [5,9,22,30],
		7: [7,12,19,29],
		8: [1,4,6,7,10,15]
	};


	var init_date = getFirstAvailableDate();
	drp.setEndDate(init_date);
	drp.setStartDate(init_date);



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



	$('#date-picker-input').on('apply.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('DD/MM/YYYY'));
	});

	$('#date-picker-input').on('cancel.daterangepicker', function(ev, picker) {
		$(this).val('');
	});

	/*$("body").on("mousedown", ".daterangepicker .calendar .next.available", function() {
		//console.log(drp.leftCalendar.month.month());
		alert(1);
	});*/





	/*$("body").on("mousedown", ".daterangepicker .calendar .next.available", function() {
		console.log(drp);
	});*/



	// Removes dates from datepicker belonging to other months, and disables unavailable dates, 
	// and then makes ajax to populate available dates with min price per day.
	function updateDatePicker()
	{
		trimExternalDates();

		disableUnavailableDates();

		displayMinimumPrices();
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

	
	// Disables the date elements whose dates are unavailable.
	function disableUnavailableDates()
	{

		var month = drp.leftCalendar.month.month();

		drp.container.find(".calendar.left table tbody td").each(function(index, elem) {

			if($(elem).hasClass("date-deleted"))
				return; // continue loop

			var dayOfMonth = parseInt(elem.innerText);

			if(unavailableDates[month].includes(dayOfMonth)) {
				$(elem).removeClass("available").addClass("off disabled");
			}

		});
	}



	function displayMinimumPrices()
	{	
		//var month = drp.leftCalendar.month.month();
		// <request takes place here>
		
		var minPricesPerDay = {
			1: 3235,
			2: 1245,
			3: 1558,
			4: false,
			5: false,
			6: false,
			7: 2405,
			8: 3245,
			9: 6945,
		};


		assignCalendarPrices(minPricesPerDay);
	}



	function assignCalendarPrices(prices)
	{
		var dayOfMonth;
		
		drp.container.find(".calendar.left table tbody td").each(function(index, elem) {

			if($(elem).hasClass("date-deleted"))
				return; // continue loop

			dayOfMonth = parseInt(elem.innerText);

			if(dayOfMonth in prices) {

				if(prices[dayOfMonth] !== false) {
					$(elem).html(dayOfMonth + "<div class='date-min-price'>" + prices[dayOfMonth] + "</div>");
				}
				else {
					$(elem).addClass("off disabled").removeClass("available").html(dayOfMonth + "<div class='date-min-price'>&nbsp;</div>"); // should already be disabled, but gets disabled in case it got unavailable after page load.
				}
				
			} else {
				$(elem).html(dayOfMonth + "<div class='date-min-price'>&nbsp;</div>");
			}

		});
	}


	function getFirstAvailableDate() {

		var date = drp.minDate;
		
		while(date <= drp.maxDate) {

			if(!unavailableDates[date.month()].includes(date.date())) {
				return date.format(DATE_FORMAT);
			}

			date = date.add(1, "days");

		}

		return drp.minDate.format(DATE_FORMAT);
	}



});


