$(document).ready(function() {

	var cropperShown = false;
	var imgCrop;

	$("#change-profile-pic").click(function() {
		$(this).parent("div").hide();
		$("#profile-pic-input").show();
	});


	$("#profile-pic-input").change(function() {


		if(!this.files[0] || !this.files[0].type.startsWith("image/"))
			return;


		if(!cropperShown) {
			cropperShown = true;

			$("#img-crop-box").show();

			imgCrop = $('.img-crop').croppie({
			    viewport: {
			        width: 200,
			        height: 200,
			        type: 'circle'
			    },
			    boundary: {
			        width: 400,
			        height: 300
			    }
			});
		}

        var reader = new FileReader(); 

        reader.onload = function (e) {
            imgCrop.croppie('bind', {
                url: e.target.result
            });
        }        
        reader.readAsDataURL(this.files[0]);

	});


	$("#upload-profile-pic").click(function() {

		var result = imgCrop.croppie("result", {
			type: "blob",
			format: "jpeg",
			circle: false,
			size: {
				width: 500,
				height: 500
			}
		}).then(function (file) {
            sendChangeProfilePicRequest(file);
        });

	});


});



function sendChangeProfilePicRequest(imgBlob)
{

	var fd = new FormData();
	fd.append('profile_pic', imgBlob, 'profilepic.jpg');

	$.ajax({
		type: "POST",
		url: app_url + "instructor/panel/cuenta/img_perfil",
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
	    data: fd,
	    processData: false,
	    contentType: false,

		beforeSend: function() {
			
		},

		success: function(response) {
			//console.log(response); 
			location.reload();
		},

		error: function (jqXhr, textStatus, errorMessage) {
			console.log(jqXhr);
			if(jqXhr.status == 422)
				alert(jqXhr.responseText);
			else
				alert("An error ocurred with the request.");
	    }

	});
}