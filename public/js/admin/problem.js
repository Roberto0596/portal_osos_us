var token = $("#token").val();
var route = "/admin/problem/show";

$(".tableProblem").dataTable({
    "destroy": true,
    "responsive": true,
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

$(".tableProblem tbody").on("click","button.btnDescription",function()
{
    var problemId = $(this).attr("idProblem");
    var route = '/admin/problem/see';
    var token = $('#tokenModal').val();
    var data = new FormData();
    data.append('problemId', problemId);
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
            $('#loader').hide();
            $("#parraf-problem").text(response["text"]);
        }
    });
});

$(".tableProblem tbody").on("click","button.btnDelete",function()
{
    var problemId = $(this).attr("idProblem");
    swal.fire({
        title: '¿estas seguro de eliminar este problema?',
        text: "¡Si no lo estas puedes cancelar!",
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
        window.location = "/admin/problem/delete/"+problemId
      }
    });
});

$(".tableProblem tbody").on("click","button.btnFixed",function()
{
    var problemId = $(this).attr("idProblem");
    swal.fire({
        title: '¿Estas seguro de que el problema esta solucionado?',
        text: "¡Si no lo estas puedes ir a verificar!",
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
        window.location = "/admin/problem/fixed/"+problemId
      }
    })
});

$("#dimiss").click(function(){
    $("#parraf-problem").text("");
    $('#loader').fadeIn(3000);
});