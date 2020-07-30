var token = $("#token").val();
var route = "/admin/reset-passwords/show";

$(".tableResetPass").dataTable({
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

$(".tableResetPass tbody").on("submit","form.from-password",function(e){
    e.preventDefault();
    var $form = $(this);
    swal.fire({
        title: '¿Está seguro de aprobar esta solicitud para reiniciar la contraseña?',
        text: "Una vez aprobada se eliminará esta solicitud",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
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