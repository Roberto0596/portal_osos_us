var token = $("#token").val();
var route = "/admin/alumns/show";

$(".tableAlumns").dataTable({
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

$(".tableAlumns tbody").on("click","button.btnDeleteAlumn",function(){
    var id = $(this).attr("alumnId");
    swal.fire({
        title: '¿Esta seguro de eliminar este alumno?',
        text: "¡todo registro de el sera borrado!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, estoy seguro'
    }).then((result)=>{
      if (result.value)
      {
        window.location = "/admin/alumns/delete/"+id;        
      }
    });
});

$(".tableAlumns tbody").on("click","button.btnUpdateAlumn",function(){
    var id = $(this).attr("alumnId");
    $('#id_alumn').val(id);
    var route = '/admin/alumns/alumnDada';
    var token = $('#token2').val();
    var data = new FormData();
    data.append('id', id);
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
            $('#PlanEstudioActual').val(response['PlanEstudio']);
            $('#EncGrupoActual').val(response['group']);
            $('#matriculaActual').val(response['enrollment']);
        }
    });
});

$('#PlanEstudioId').change(function(){
    var planEstudio = $(this).val();
    var route = '/admin/alumns/generateEnrollment';
    var token = $('#token2').val();
    var data = new FormData();
    data.append('planEstudio', planEstudio);
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
            $('#matriculaGenerada').val(response);
        }
    });
});