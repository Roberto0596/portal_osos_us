
const token = $("#token").val();
const loadHighAveragesTable = function (peroid){

    let route = `/admin/high-averages/load/${peroid}`;

    $(".tableHighAverges").dataTable({
        "destroy": true,
        "responsive": true,
        "ajax":
        {
            url: route,
            headers:{'X-CSRF-TOKEN':token},
            type: "POST",
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

};



const peroidId = $('#period').val();
loadHighAveragesTable(peroidId);



$('#period').on('change', function() {
    const period = this.value;
    loadHighAveragesTable(period);
});





