//var doSubmit = false;
var installmentInfo;


$(document).ready(function() {


	Mercadopago.getIdentificationTypes();

	addEvent(document.querySelector('input[data-checkout="cardNumber"]'), 'keyup', guessingPaymentMethod);
	addEvent(document.querySelector('input[data-checkout="cardNumber"]'), 'keyup', clearOptions);
	addEvent(document.querySelector('input[data-checkout="cardNumber"]'), 'change', guessingPaymentMethod);
	cardsHandler();


	var paymentType = $("#payment-method-select").val(); // "card" 

    $("#payment-method-select").change(function() {

        if($(this).val() == "card") {
            paymentType = "card";
            $("#credit_card_fields").show();
            $("#card_number").val("");
        }
        else {
            paymentType = "offline";
            $("#credit_card_fields").hide();
            $("input[name=paymentMethodId]").val($(this).val());            
        }
        $("input[name=payment_type]").val(paymentType);
    });



	$("button.purchase").click(function() {

		if(!validateForm())
			return;

        disablePurchaseBtn();

        if(paymentType == "card") {

            Mercadopago.createToken($("#payment-form"), function(status, response) {

                if (status != 200 && status != 201) {
                    if(typeof response.cause != "undefined")
                        displayCardError(response.cause);
                    else
                        alert("Error obteniendo autorización de tarjeta.");

                    enablePurchaseBtn();
                    return;
                }

                if($("#installments").val() == null || $("#installments").val() == -1) {
                    alert("Selecciona la cantidad de cuotas a pagar.");
                    enablePurchaseBtn();
                    return;
                }

               $("input[name=card_token]").val(response.id);

               $("#payment-form").submit();
            });
        }
        else {
            $("#payment-form").submit();
        }


	});


    /**
     * Update total breakdown with MercadoPago interests if chosen with installments.
     */
	$("#installments").change(function() {
		
		var installment_amount = $(this).val();

		if(installment_amount == -1 || installment_amount == 1) {
			$("#interest-amt").parent().hide();
			$("#total").text("$" + $("#amount").val());
		}
		else {

			$("#interest-amt").parent().show();

			for(let info of installmentInfo) {
				
				if(info.installments == installment_amount) {
					$("#installment-number").text(info.installments);
					$("#interest-amt").text( "$" + round(info.total_amount - $("#amount").val()) );
					$("#total").text("$" + info.total_amount);
					break;
				}

			}

		}

	});


});


/**
 * Displays card errors returned from MP SDK's createToken()
 */
function displayCardError(causes)
{
	causes.forEach(function(cause) {

		// poner errores en cada campo
		if(cause.code == "212")
			alert("Ingresa tipo de documento.");

		if(cause.code == "324")
			alert("El documento ingresado es inválido.");

		if(cause.code == "E301")
			alert("Ingresa número de tarjeta válido.");

		if(cause.code == "325")
			alert("El mes de exp de la tarjeta es inválido.");

		if(cause.code == "326")
			alert("El año de exp de la tarjeta es inválido.");

		if(cause.code == "E302")
			alert("El cód de seguridad de la tarjeta es inválido.");

	});
}



function validateForm()
{
	var errors = [];

	if($('input[name=phone]').length && $("input[name=phone]").val() == "")
		errors.push("Teléfono inválido.");

	if($("input[name=address]").val() == "")
		errors.push("Dirección de facturación inválida.");

	if($("input[name=address_city]").val() == "")
		errors.push("Ciudad inválida.");

	if($("input[name=address_state]").val() == "")
		errors.push("Provincia inválida.");

	if($("input[name=address_postal_code]").val() == "")
		errors.push("Código postal inválido.");


	if(errors.length == 0) 
		return true;

	else {
		alert(errors.join("\n"));
		
		return false;
	}
}


function disablePurchaseBtn() {
   $("button.purchase").prop("disabled", true);
   $("button.purchase").html("Pagar&nbsp;&nbsp;<i class='fas fa-spinner fa-spin'></i>");
}

function enablePurchaseBtn() {
   $("button.purchase").prop("disabled", false);
   $("button.purchase").html("Pagar");
}





function addEvent(el, eventName, handler){
    if (el.addEventListener) {
           el.addEventListener(eventName, handler);
    } else {
        el.attachEvent('on' + eventName, function(){
          handler.call(el);
        });
    }
};



function getBin() {
    var cardSelector = document.querySelector("#cardId");
    if (cardSelector && cardSelector[cardSelector.options.selectedIndex].value != "-1") {
        return cardSelector[cardSelector.options.selectedIndex].getAttribute('first_six_digits');
    }
    var ccNumber = document.querySelector('input[data-checkout="cardNumber"]');
    return ccNumber.value.replace(/[ .-]/g, '').slice(0, 6);
}

function clearOptions() {
    var bin = getBin();
    if (bin.length == 0) {
        $("#issuer").parent().hide();
        //document.querySelector("#issuer").style.display = 'none';
        document.querySelector("#issuer").innerHTML = "";

        var selectorInstallments = document.querySelector("#installments"),
            fragment = document.createDocumentFragment(),
            option = new Option("Elegir...", '-1');

        selectorInstallments.options.length = 0;
        fragment.appendChild(option);
        selectorInstallments.appendChild(fragment);
        selectorInstallments.setAttribute('disabled', 'disabled');
    }
}

function guessingPaymentMethod(event) {
    var bin = getBin(),
        amount = document.querySelector('#amount').value;
    if (event.type == "keyup") {
        if (bin.length == 6) {
            Mercadopago.getPaymentMethod({
                "bin": bin
            }, setPaymentMethodInfo);
        }
    } else {
        setTimeout(function() {
            if (bin.length >= 6) {
                Mercadopago.getPaymentMethod({
                    "bin": bin
                }, setPaymentMethodInfo);
            }
        }, 100);
    }
};

function setPaymentMethodInfo(status, response) {
    if (status == 200) {
        // do somethings ex: show logo of the payment method
        var form = document.querySelector('#payment-form');

        if (document.querySelector("input[name=paymentMethodId]") == null) {
            var paymentMethod = document.createElement('input');
            paymentMethod.setAttribute('name', "paymentMethodId");
            paymentMethod.setAttribute('type', "hidden");
            paymentMethod.setAttribute('value', response[0].id);
            form.appendChild(paymentMethod);
        } else {
            document.querySelector("input[name=paymentMethodId]").value = response[0].id;
        }

        // check if the security code (ex: Tarshop) is required
        var cardConfiguration = response[0].settings,
            bin = getBin(),
            amount = document.querySelector('#amount').value;

        for (var index = 0; index < cardConfiguration.length; index++) {
            if (bin.match(cardConfiguration[index].bin.pattern) != null && cardConfiguration[index].security_code.length == 0) {
                $("input[data-checkout='securityCode']").closest(".col-md-6").hide();
            } else {
                $("input[data-checkout='securityCode']").closest(".col-md-6").show();
            }
        }

        Mercadopago.getInstallments({
            "bin": bin,
            "amount": amount
        }, setInstallmentInfo);

        // check if the issuer is necessary to pay
        var issuerMandatory = false,
            additionalInfo = response[0].additional_info_needed;

        for (var i = 0; i < additionalInfo.length; i++) {
            if (additionalInfo[i] == "issuer_id") {
                issuerMandatory = true;
            }
        };
        if (issuerMandatory) {
            Mercadopago.getIssuers(response[0].id, showCardIssuers);
            addEvent(document.querySelector('#issuer'), 'change', setInstallmentsByIssuerId);
        } else {
            //document.querySelector("#issuer").style.display = 'none';
            $("#issuer").parent().hide();
            document.querySelector("#issuer").options.length = 0;
        }
    }
};

function showCardIssuers(status, issuers) {
    var issuersSelector = document.querySelector("#issuer"),
        fragment = document.createDocumentFragment();

    issuersSelector.options.length = 0;
    var option = new Option("Elegir...", '-1');
    fragment.appendChild(option);

    for (var i = 0; i < issuers.length; i++) {
        if (issuers[i].name != "default") {
            option = new Option(issuers[i].name, issuers[i].id);
        } else {
            option = new Option("Otro", issuers[i].id);
        }
        fragment.appendChild(option);
    }
    issuersSelector.appendChild(fragment);
    issuersSelector.removeAttribute('disabled');

    $("#issuer").parent().show();
    //document.querySelector("#issuer").removeAttribute('style');
};

function setInstallmentsByIssuerId(status, response) {
    var issuerId = document.querySelector('#issuer').value,
        amount = document.querySelector('#amount').value;

    if (issuerId === '-1') {
        return;
    }

    Mercadopago.getInstallments({
        "bin": getBin(),
        "amount": amount,
        "issuer_id": issuerId
    }, setInstallmentInfo);
};

function setInstallmentInfo(status, response) {

    var selectorInstallments = document.querySelector("#installments"),
        fragment = document.createDocumentFragment();

    selectorInstallments.options.length = 0;

    if (response.length > 0) {
        var option = new Option("Elegir...", '-1'),
            payerCosts = response[0].payer_costs;
        installmentInfo = payerCosts;

        fragment.appendChild(option);
        for (var i = 0; i < payerCosts.length; i++) {
            option = new Option(payerCosts[i].recommended_message || payerCosts[i].installments, payerCosts[i].installments);
            fragment.appendChild(option);
        }
        selectorInstallments.appendChild(fragment);
        selectorInstallments.removeAttribute('disabled');
    }
};



function cardsHandler() {
    clearOptions();
    var cardSelector = document.querySelector("#cardId"),
        amount = document.querySelector('#amount').value;

    if (cardSelector && cardSelector[cardSelector.options.selectedIndex].value != "-1") {
        var _bin = cardSelector[cardSelector.options.selectedIndex].getAttribute("first_six_digits");
        Mercadopago.getPaymentMethod({
            "bin": _bin
        }, setPaymentMethodInfo);
    }
}

function round(num)
{
	return Math.round(num * 100) / 100;
}