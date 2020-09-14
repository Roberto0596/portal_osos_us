var token = $("#token").val();
var route = "/alumn/documents/show";

$(".tableDocuments").dataTable({
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

$(".tableDocuments tbody").on("click","button.reload",function(){
    location.reload();
});

$(".open-modal").click(function(){
    var type = $(this).attr("document-type");
    $("#document-type").val(type);
});

$(".tap-change").click(function(){
    var val = $(this).attr("data-value");
    var route = 'tab/see';
    var token = $("#token").val();
    var data = new FormData();
    data.append("tab",val);
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
         
      }
  });
});

$('#document-upload').change(function()
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
        toastr.success("Extensión no permitida: " + ext);
      }
    }
  });