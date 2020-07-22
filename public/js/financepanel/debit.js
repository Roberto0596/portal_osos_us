var token = $("#token").val();
var route = "/finance/debit/show";

$(".tableDebits").dataTable({
    "destroy": true,
    "ajax":
    {
        url: route,
        headers:{'X-CSRF-TOKEN':token},
        type: "PUT",
    },
    "language": {

        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }

    }

});

$("#id_alumno").select2({
    width: 'resolve'
});




$(".tableDebits tbody").on("click","button.edit",function()
{
    var DebitId = $(this).attr("DebitId");
    var route = '/finance/debit/see';
    var token = $('#tokenModal').val();
    var data = new FormData();
    data.append('DebitId', DebitId);
    $.ajax({
        url:route,
        headers:{'X-CSRF-TOKEN': token},
        method:'POST',
        data:data,
        cache:false,
        contentType:false,
        processData:false,
        success:function(response)
        {
            if (response["status"]==1)
            {
                $('#EditStatus').removeAttr("name");
                $('#EditStatus').attr("readonly","readonly");
                $('#EditId_alumno').removeAttr("name");
                $('#EditId_alumno').attr("readonly","readonly");
                $('#EditAmount').removeAttr("name");
                $('#EditAmount').attr("readonly","readonly");
            }  
            else
            {
                $('#EditStatus').attr("name","EditStatus");
                $('#EditStatus').removeAttr("readonly");
                $('#EditId_alumno').attr("name","EditId_alumno");
                $('#EditId_alumno').removeAttr("readonly");
                $('#EditAmount').attr("name","EditAmount");
                $('#EditAmount').removeAttr("readonly");
            }        
            $('#EditConcept').val(response['concept']);
            $('#EditAmount').val(response['amount']);
            $('#EditId_alumno').val(response['alumnId']);
            $('#EditStatus').val(response['status']);
            $('#DebitIdUpdate').val(response['debitId']);
            $('#EditDescription').val(response['description']);           
           
        }});
});

$(".tableDebits tbody").on("click","button.showPdf",function()
{
  var route = $(this).attr("route");
  window.open("/"+route,"_blank");
})

$(".tableDebits tbody").on("click","button.details",function()
{  
    var DebitId = $(this).attr("DebitId");
    var is = $(this).attr("is");
    var route = '/finance/debit/payment-details';
    var token = $('#tokenModal').val();
    var data = new FormData();
    data.append('DebitId', DebitId);
    data.append('is', is);
    $.ajax({
        url:route,
        headers:{'X-CSRF-TOKEN': token},
        method:'POST',
        data:data,
        cache:false,
        contentType:false,
        processData:false,
        success:function(response)
        {     
            if (response["type"]=="card")
            {
                $('#loader').hide();
                $('#detail-id').text("ID: " +response['id']);
                $('#detail-paymentMethod').text("Método de pago: " +response['paymentMethod']);
                $('#detail-reference').text("Sin referencia");
                $('#detail-amount').text("Monto: " +response['amount']);
                $('#detail-order').text("Orden: " +response['order']);
            }   
            else
            {
                $('#loader').hide();
                $('#detail-id').text("ID: " +response['id']);
                $('#detail-paymentMethod').text("Método de pago: " +response['paymentMethod']);
                $('#detail-reference').text("Referencia: " +response['reference']);
                $('#detail-amount').text("Monto: " +response['amount']);
                $('#detail-order').text("Orden: " +response['order']);
            }                      
        }
    });
    $('#loader').show();
});

