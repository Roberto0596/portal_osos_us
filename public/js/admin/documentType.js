let token = $("#token").val();
let route = "/admin/document-type/show";



$(".tableDocumentType").dataTable({
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

$("#typeCreate").change( function() {
    if ($(this).val() === "1") {
        $("#cost").prop("disabled", false);
    } else {
        $("#cost").prop("disabled", true);
    }
});

$("#typeUpdate").change( function() {
    if ($(this).val() === "1") {
        $("#costUpdate").prop("disabled", false);
    } else {
        $("#costUpdate").prop("disabled", true);
    }
});




 $(".tableDocumentType tbody").on("click","button.btnDelete",function()
{
   
    let doc_type_id = $(this).attr("doc_type_id");
    swal.fire({
        title: '¿Estas seguro de eliminar este tipo de documento?',
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
        window.location = "/admin/document-type/delete/"+doc_type_id
      }
    }); 
    
  
});



$(".tableDocumentType tbody").on("click","button.btnEdit",function()
 {

   
   
    var dataToSend = new FormData();
    let doc_type_id = $(this).attr("doc_type_id");
    dataToSend.append(
        'id', doc_type_id
    );

    $.ajax({
        url:'/admin/document-type/seeDoc',
        headers:{'X-CSRF-TOKEN': $('#tokenUpdate').val()},
        method:'POST',
        data:dataToSend,
        cache:false,
        contentType:false,
        processData:false,
        success:function(response)
        {   

           $("#can_deleteUpdate").val(response["can_delete"]);
           $("#typeUpdate").val(response["type"]);

           if(response["type"] == 1){
            $("#costUpdate").prop("disabled", false);
           }else{
            $("#costUpdate").prop("disabled", true);
           }

            $('#nameUpdate').val(response['name']);
            $('#costUpdate').val(response['cost']);
            $('#idToUpdate').val(doc_type_id);  
            $("#modalEdit").modal("show");  
          
                       
        }
    });
    
 });











