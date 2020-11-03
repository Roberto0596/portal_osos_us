
$(".tableFailed").dataTable({
    "destroy": true,
    "responsive": true,
    "ajax":
    {
        url: "/admin/failed/registers/show",
        headers:{'X-CSRF-TOKEN': $("#token").val()},
        type: "POST",
    },
    "searching": false,
    serverSide:true,
    "columns":[
            {"data": "#"},
            {"data": "Alumno"},
            {"data": "Periodo"},
            {"data": "Mensaje"},
            {"data": null, orderable: false, "render": function(data){
                var res = "";

                if (data.status == 0) {
                    res += "Sin resolver";
                } else {
                    res += "Resuelto";
                }
                return res;
            }},
            {"data": "Fecha"},
            {"data": null, orderable: false, "render": function(data){
                var res = "<div class='btn-group'><button failedId = '"+data.id+"' class='btn btn-success btn-show-modal' modal-target='#modalFix'>Arreglar</button></div>"
                return res;
            }},
        ],
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

$(".tableFailed tbody").on("click","button.btn-show-modal",function()
{
    $("#failedId").val($(this).attr("failedId"));
    $("#modalFix").modal("show");
});


$("#semestre").change(function() {
    var data = new FormData();
    data.append('semestre', $(this).val());
    $.ajax({
        url:'/admin/failed/registers/encGrupo',
        headers:{'X-CSRF-TOKEN': $("#tokenModal").val()},
        method:'POST',
        data:data,
        cache:false,
        contentType:false,
        processData:false,
        success:function(response)
        {  
            console.log(response.length);
            if(response.length == 0) {
                $('#encGrupo').empty();
                $('#encGrupo').prepend("<option value=''>No hay grupos</option>");
            } else {
                $('#encGrupo').empty();
                for (var i = 0; i < response.length; i++) {
                    $('#encGrupo').prepend("<option value='"+response[i].EncGrupoId+"'>"+response[i].Nombre+"</option>");
                } 
            }
     
        }
    });
})
