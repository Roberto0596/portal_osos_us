const options2 = { style: 'currency', currency: 'USD' };
const numberFormat2 = new Intl.NumberFormat('en-US', options2);

$(".debit").change(function() {
    var debitId = $(this).attr("debitId");
    var price = $(this).attr("price");
    var total = $("#total");
    var hiddenTotal = $("#total-hidden");
    if($(this).prop('checked')) {
        var finalTotal = parseInt(hiddenTotal.val()) + parseInt(price);
        total.html(numberFormat2.format(finalTotal));
        hiddenTotal.val(finalTotal);
        listDebit();
    } else {
        var finalTotal = parseInt(hiddenTotal.val()) - parseInt(price);
        total.html(numberFormat2.format(finalTotal));
        hiddenTotal.val(finalTotal);
        listDebit();
    }
});


function listDebit()
{
    var listaProductos =[];
    var ids = $(".debit");

    for(var i=0; i<ids.length; i++)
    {
        if($(ids[i]).prop('checked')) {
            listaProductos.push({ "id" : $(ids[i]).attr("debitId")});
        }
    }
    $(".debitList").val(JSON.stringify(listaProductos));
}

$("#card-button").click(function() {
    $("#modal").modal("hide");
    $("#modalCard").modal("show");
});

$("#payment").click(function() {
    if ($("#total-hidden").val() > 0) {
        $("#modal").modal("show");
        getCost("card");
        getCost("oxxo");
        getCost("spei");
        getCost("transfer");
    } else {
        swal.fire({
            title: "Aun no selecciona un adeudo",
            text: "¡Debe seleccionar por lo menos uno!",
            type: "info",
            conmfirmButtonText:"¡cerrar!"
        });
    }
});

function getCost(element) {
    var array = getTotalWithComission($("#total-hidden").val(), element);
    if (element != "transfer") {
        $("#total-"+element).text(numberFormat2.format(array.total));
        $("#total-comission-"+element).text(numberFormat2.format(array.comission));
    } else {
        $("#total-"+element).text(numberFormat2.format($("#total-hidden").val()));
    }
    $("#total-adeudo-"+element).text(numberFormat2.format($("#total-hidden").val()));
}

function getTotalWithComission(total, tipo) {
  if (tipo == "card") {
    comission = (1 - (0.029 * 1.16));
    comission_fixed = 2.5 * 1.16;
    total_payment = ((parseFloat(total) + comission_fixed)/comission);
    total_comission = total_payment - parseFloat(total);
  } else if (tipo == "oxxo") {
    comission = (1 - (0.039 * 1.16));
    total_payment = parseFloat(total)/comission;
    total_comission = total_payment - parseFloat(total);
  } else if (tipo == "spei") {
    comission = 12.5 * 1.16;
    total_payment = parseFloat(total) + comission;
    total_comission = total_payment - parseFloat(total);
  }

  return {total: total_payment, comission: total_comission};
}

// jquery de la tarjeta
  $('.input-cart-number').on('keyup change', function()
  {
    $t = $(this);
    if ($t.val().length == 4) 
    {
        $t.next().focus();
        $fullnumber=  $t.val();
    }  
    var card_number = '';
    var fullCardNumber = '';
    $('.input-cart-number').each(function()
    {
        card_number += $(this).val() + ' ';
        fullCardNumber  += $(this).val();
          
        if ($(this).val().length == 4) 
        {
             $(this).next().focus();
        }  
    })
    $("#fullCardNumber").val(fullCardNumber);   
    $('.credit-card-box .number').html(card_number);  
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

  $("#form-oxxo").submit(function(e)
{
    var $form = $("#form-oxxo");
    e.preventDefault();
    swal.fire({
        title: '¿estas seguro de pagar con oxxo pay?',
        text: "¡solo pudes cancelar una sola vez!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, estoy seguro'
    }).then((result)=>
    {
        if (result.value)
        {
            $form.get(0).submit();
        }
    });
});

$("#form-spei").submit(function(e)
{
    var $form = $("#form-spei");
    e.preventDefault();
    swal.fire({
        title: '¿estas seguro de pagar con SPEI?',
        text: "¡solo pudes cancelar una sola vez!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, estoy seguro'
    }).then((result)=>
    {
        if (result.value)
        {
            $form.get(0).submit();
        }
    });
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