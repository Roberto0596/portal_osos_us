var token = $("#token").val();
var route = "/computo/debit/show";

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

$(".tableDebits tbody").on("click","button.pay",function()
{
    var DebitId = $(this).attr("DebitId");
    var route = '/computo/debit/see';
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
            $("#body-table").empty();
            $("#body-table").append("<tr>"+
                    "<td>"+response["concept"]+"</td>"+
                    "<td>"+response["description"]+"</td>"+
                    "<td>"+response["alumnName"]+"</td>"+
                    "<td>"+response["amount"]+"</td>"+
                "</tr>");
            $("#DebitId").val(response["debitId"]);
        }});
});