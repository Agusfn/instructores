var page = 0;

$(document).ready(function() {

	load_more_results();

	$("#load-more").click(function() {
		load_more_results();
	});

	$("input[name=sort_results]").change(function() {
		insertParam("sort", $(this).val());
	});

});



function load_more_results()
{
	page += 1;

	$.ajax({
		type: "POST",
		url: app_url + "buscar/resultados?page=" + page,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
		data: { 
			sort: sort,
			discipline: discipline,
			date: date,
			qty_adults: qty_adults,
			qty_kids: qty_kids
		},

		beforeSend: function() {
			// put loading spinner
		},
		success: function(response) {
			console.log(response);
			search_response(response);
		},
		error: function (jqXhr, textStatus, errorMessage) {
			if(jqXhr.status == 422)
				alert(jqXhr.responseText);
			else
				alert("An error ocurred with the request.");
	    }
	});

}

function search_response(response)
{
	
	if(response.data.length == 0) {
		if(page == 1)
			$("#no-results").show();
		$("#load-more").parent().hide();
		return;
	}

	response.data.forEach(function(instructorService) {
		place_result(instructorService);
	});


	if(response.last_page == page)
		$("#load-more").parent().hide();

}


function place_result(service)
{
	var service_url = app_url + "instructor/" + service.number;
	var profile_pic_url = app_url + "storage/img/instructors/" + service.instructor.profile_picture;

	var html = '\
	<div class="col-xl-4 col-lg-6 col-md-6">\
		<div class="box_grid">\
			<figure>\
				\
				<a href="' + service_url + '"><img src="' + profile_pic_url + '" class="img-fluid" alt="" width="800" height="533"><div class="read_more"><span>Ver más</span></div></a>\
				<small>Nivel ' + service.instructor.level + '</small>\
			</figure>\
			<div class="wrapper">\
				<!--div class="cat_star"><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i></div-->\
				<h3><a href="' + service_url + '">' + service.instructor.name + '</a></h3>\
				';
				
				if(service.snowboard_discipline && service.ski_discipline)
					html += "<p>Instructor de ski y snowboard</p>";
				else if(service.snowboard_discipline)
					html += "<p>Instructor de snowboard</p>";
				else
					html += "<p>Instructor de ski</p>";
				
				html += '\
				<!--p>Idiomas: Ingles/español</p-->\
				<span class="price"> <strong>$' + service.quote.classes_price + '</strong> /2 horas (' + service.quote.person_amt + ' personas)</span>\
			</div>\
			<!--ul>\
				<li><div class="score"><span>Super instructor<em>350 Reseñas</em></span><strong>9.6</strong></div></li>\
			</ul-->\
		</div>\
	</div>\
	';

	$("#search-results .row").append(html);


}


function insertParam(key, value)
{
    key = encodeURI(key); value = encodeURI(value);
    var kvp = document.location.search.substr(1).split('&');
    var i=kvp.length; var x; while(i--) 
    {
        x = kvp[i].split('=');
        if (x[0]==key)
        {
            x[1] = value;
            kvp[i] = x.join('=');
            break;
        }
    }
    if(i<0) {kvp[kvp.length] = [key,value].join('=');}
    document.location.search = kvp.join('&'); 
}