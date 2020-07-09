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

	$("#card-form").submit(function(e)
	{
	  e.preventDefault();
	  var $form = $("#card-form");
	  Conekta.Token.create($form,successResponseHandler,errorResponseHandler);
	});
});

$('#ticket').change(function()
{
	var file = this.files[0];
	var ext = file['type'];
	console.log(ext);
	if ($(this).val() != '') 
	{
	  if(ext == "application/pdf")
	  {
		if(file["size"] > 1048576)
		{
			toastr.error("Se solicita un archivo no mayor a 1MB. Por favor verifica.");
			$(this).val('');
		}
		else
		{
			toastr.success("Formato permitido");
		}
	  }
	  else
	  {
		$(this).val('');
		alert("Extensión no permitida: " + ext);
	  }
	}
  });
  


  // jquery de la tarjeta
  $('.input-cart-number').on('keyup change', function(){

	if ($t.val().length == 4) {
    $t.next().focus();
    $fullnumber=  $t.val();
	}
  
  var card_number = '';
  var fullCardNumber = '';
	$('.input-cart-number').each(function(){
    card_number += $(this).val() + ' ';
    fullCardNumber  += $(this).val();
	  if ($(this).val().length == 4) {
    $(this).next().focus();
    
    }
   
  })

  
  $('.credit-card-box .number').html(card_number);



  // aqui estaba queriendo ponerle al campo hidden el numero de tarjeta completo
 
  if(cleanCardNumber.length == 16){
    $("#full-card-number").val(fullCardNumber);
  }
  
  });
  
  $('#card-holder').on('keyup change', function(){
	$t = $(this);
	$('.credit-card-box .card-holder div').html($t.val());
  });
  
  $('#card-holder').on('keyup change', function(){
	$t = $(this);
	$('.credit-card-box .card-holder div').html($t.val());
  });
  
  $('#expire-month, #expire-year').change(function(){
	m = $('#expire-month option').index($('#expire-month option:selected'));
	m = (m < 10) ? '0' + m : m;
	y = $('#expire-year').val().substr(2,2);
	$('.card-expiration-date div').html(m + '/' + y);
  })
  
  $('#card-ccv').on('focus', function(){
	$('.credit-card-box').addClass('hover');
  }).on('blur', function(){
	$('.credit-card-box').removeClass('hover');
  }).on('keyup change', function(){
   let temp = $(this).val();
   let obscureText = ''; 
    for (let index = 0; index < temp.length; index++) {
     obscureText = obscureText + '·';
      
    }
	$('.ccv div').html(obscureText);
  });
  
  setTimeout(function(){
	$('#card-ccv').focus().delay(1000).queue(function(){
	  $(this).blur().dequeue();
	});
  }, 500);

  