// Quantity buttons
	function qtySum(){
    var arr = document.getElementsByClassName('qtyInput');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }

    var cardQty = document.querySelector(".qtyTotal");
    cardQty.innerHTML = tot;
	} 
	qtySum();

	$(function() {

		$(".qtyButtons input").after('<div class="qtyInc"></div>');
	   	$(".qtyButtons input").before('<div class="qtyDec"></div>');
	   	$(".qtyDec, .qtyInc").on("click", function() {

			var $button = $(this);
		  	var oldValue = $button.parent().find("input").val();

		  	var input = $button.parent().find("input");
		  	var newVal;

		  	if ($button.hasClass('qtyInc')) {

		  		if(!input.data("max") || oldValue < parseInt(input.data("max")))
		 			newVal = parseInt(oldValue) + 1;
		 		else
		 			newVal = parseInt(oldValue);

		  	} else {
				// don't allow decrementing below zero
			 	if (oldValue > 0) {

			 		if(!input.data("min") || oldValue > parseInt(input.data("min")))
						newVal = parseInt(oldValue) - 1;
		 			else
		 				newVal = parseInt(oldValue);

			 	} else {
					newVal = 0;
			 	}
		  	}

		  	input.val(newVal);
		  	qtySum();
		  	$(".qtyTotal").addClass("rotate-x");

		});

	   	function removeAnimation() { $(".qtyTotal").removeClass("rotate-x"); }
	   	const counter = document.querySelector(".qtyTotal");
	   	counter.addEventListener("animationend", removeAnimation);

	});