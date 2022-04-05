var token = $("#token").val();
var route = "/alumn/documents/show";

function loadTable() {
  $(".tableDocuments").dataTable({
      "destroy": true,
      "processing":true,
      "responsive": true,
      serverSide: true,
      stateSave: true,
      "ajax":
      {
          url: route,
          headers:{'X-CSRF-TOKEN':token},
          type: "POST",
      },
      "columns":[
          {"data": null, orderable: false, render:function(data) {
            return data.document_type.name;
          }},
          {"data": "description"},
          {"data": null, orderable: false, render: function(data) {
            return data.period.clave;
          }},
          {"data": "created_at"},
          {"data": null, "render": function(data) {
            console.log(data);
            var res = "<div class='btn-group'>";
            if (data.payment == 0) {
                res += "<button class='btn btn-danger btnCancelDocument' title='Imprimir' id_document='"+data.id+"'>"+
                    "Cancelar</button>"+
                    "</div>";
            } else if (data.route != null && data.route != "") {
                res += "<a class='btn btn-primary reload' target='_blank' href='documents/redirectTo?id="+data.id+"&route="+data.route+"' title='Imprimir'>"+
                "Imprimir</a>"+
                "</div>";
            } else {
                res += "<button class='btn btn-default'>"+
                    "En proceso</button>";
            }

            return res + "</div>";
          }}
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

    $(".tableDocuments tbody").on("click","button.reload",function(){
        location.reload();
    });

    $(".tableDocuments tbody").on("click","button.btnCancelDocument",function() {
        var id =  $(this).attr("id_document");
        swal.fire({
            title: '¿Estas seguro/a de que quieres cancelar este documento?',
            text: "¡Si lo haces se borrara el registro!",
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
                  window.location = "/alumn/pdf/delete/document/"+id;
              }
          });
    });
}
  
$(document).ready(function() {
    loadTable();
})

$(".open-modal").click(function(){
    var type = $(this).attr("document-type");
    $("#document-type").val(type);
});

$(".tap-change").click(function(){
    var val = $(this).attr("data-value");

    if (val == 0) {
        loadTable();
    }
    
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

$(".form-document").submit(function(e)
{
    var $form = $(this);
    e.preventDefault();
    swal.fire({
        title: '¿Estas seguro/a de que quieres solicitar este documento?',
        text: "¡Si aceptas se generara un adeudo!",
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