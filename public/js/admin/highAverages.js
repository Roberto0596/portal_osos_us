
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








const clearSeacrhinputAndTable = function(){
    $('#searchAlumn').val("");
    $("#tableBody").html(null);
}


//sirve para hacer peticiones hasta que se deje de escribir en el input 
//y la peticion no se haga cada vez que se presione una tecla
function debounce(func, wait, immediate) {
    let timeout;
    return function() {
        let context = this, args = arguments;
        let later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        let callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};


//funcion que realiza la peticion
var search = debounce(function() {

  
    const token = $('#tokenSearch').val();
    const route = `/admin/high-averages/search`;


    $.ajax({
       url: route,
       headers: {'X-CSRF-TOKEN': token},
       type: "post",
       dataType: "json",
       data: { enrollment: $('#searchAlumn').val() },
       success: function (response) {
          $("#tableBody").html(response);
       }
    });
 
 }, 900);


 //buscar alumno
$('#searchAlumn').on('keydown', search);
$("#modalHighAverages").on("hidden.bs.modal", clearSeacrhinputAndTable);




$(".tableHighAverges tbody").on("click","button.btnDelete",function()
{
   
    const high_average_id = $(this).attr("high_average_id");
    swal.fire({
        title: '¿Estas seguro de eliminar este Alumno?',
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
        window.location = `/admin/high-averages/delete/${high_average_id}`;
      }
    }); 
    
  
});


$(".tableAlumns tbody").on("click","button.btnAdd",function(event)
{


    event.preventDefault();
    $('#searchAlumn').val($(this).attr("alumn_enrollment"));
    $('#periodId').val($('#period').val());

    $('#addAlumn').submit();
});


