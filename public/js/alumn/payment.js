var step = 0;

$(document).ready(function(){
	$("#hidden-2").css("display","none");
})

$("#next").click(function()
{
	$("#extra").fadeOut(500);
	$(this).fadeOut(500);
	$("#hidden-1").fadeOut(500,function(){
		$("#hidden-2").fadeIn(500);
		$("#back").fadeIn(500);
	});
	// $(this).attr("data-toggle","modal");
	// $(this).attr("data-target","#modalPago");
	step = 1;
});

$("#back").click(function()
{
	$(this).fadeOut(500,function(){
		$("#hidden-2").fadeOut(500,function(){
			$("#next").fadeIn(500);
			$("#extra").fadeIn(500);
			$("#hidden-1").fadeIn(500);
		});
	});
});

$("#payment-card").click(function(){
	console.log("hola");
	$("#modalCard").modal("show");
});

$(document).ready(function()
{
	Conekta.setPublicKey('key_JgCLUGDrLiCoyFFhYxKYgFw');

	var successResponseHandler = function(token){
	  var $form = $("#card-form");
	  $form.append($('<input type="hidden" name="conektaTokenId" id="conektaTokenId">').val(token.id));
	  $form.get(0).submit();
	}

	var errorResponseHandler = function(error){
	  toastr.error("hubo un problema del tipo " + error.message_to_purchaser);
	}

	$("#card-form").submit(function(e){
	  e.preventDefault();
	  var $form = $("#card-form");
	  Conekta.Token.create($form,successResponseHandler,errorResponseHandler);
	});
});