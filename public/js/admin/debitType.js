let token = $("#token").val();
let route = "/admin/debit-type/show";



$(".tableDebitType").dataTable({
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



$(".tableDebitType tbody").on("click","button.btnDelete",function()
{
   
     let debit_type_id = $(this).attr("debit_type_id");
    swal.fire({
        title: '¿Estas seguro de eliminar este tipo de adeudo?',
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
        window.location = "/admin/debit-type/delete/"+debit_type_id
      }
    }); 
    
  
});


$(".tableDebitType tbody").on("click","button.btnEdit",function()
 {
    var dataToSend = new FormData();
    let debit_type_id = $(this).attr("debit_type_id");
    dataToSend.append(
        'id', debit_type_id
    );

    $.ajax({
        url:'/admin/debit-type/seeDebitType',
        headers:{'X-CSRF-TOKEN': $('#tokenUpdate').val()},
        method:'POST',
        data:dataToSend,
        cache:false,
        contentType:false,
        processData:false,
        success:function(response)
        {   

            $("#modalDebitTypeEdit").modal("show"); 
            $("#can_delete_edit option[value=" + response["can_delete"]+"]").attr("selected",true);
            $('#conceptEdit').val(response['concept']);
            $('#idToUpdate').val(debit_type_id);
          
                       
        }
    });
   
 });







