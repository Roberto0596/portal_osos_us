function changeMode(mode){
    var token = $("#token").val();
    var route = "/finance/debit/show";
    $(".tableDebits").dataTable({
        serverSide: true,
        "destroy": true,
        "deferRender": true,
        "retrieve": true,
        "processing": true,
        "responsive": true,
        "ajax":
        {
            url: route,
            headers:{'X-CSRF-TOKEN':token},
            type: "PUT",
            data: {mode:mode}
        },
        "columns":[
            {"data": "#"},
            {"data": null, orderable: false, "render": function(data){
                var res="<div class='btn-group'>"+
                "<button class='btn btn-warning edit' data-toggle='modal' data-target='#modalEdit' DebitId='"+data.debitId+"' title='Editar Adeudo'>"+
                  "<i class='fa fa-edit' style='color:white'></i></button>";

                if (data.method=="transfer") {
                    res+="<button class='btn btn-success showPdf' route='"+data.id_order+"'><i class='fa fa-file' title='Ver detalles del pago' style='color:white'></i></button>";
                } else if(data.id_order != null) {
                    res+="<button class='btn btn-danger custom details' data-toggle='modal' data-target='#modalShowDetails' is='"+data.method+"' DebitId='"+data.debitId+"'>"+
                    "<i class='fa fa-eye' title='Ver detalles del pago' style='color:white'></i></button>";
                }
                return res;
            }},
            {"data": "Alumno"},
            {"data": "Email"},
            {"data": "Descripción"},
            {"data": "Importe"},
            {"data": "Matricula"},
            {"data": "Estado"},
            {"data": "Fecha"},
            {"data": "Carrera"},
            {"data": "Localidad"},
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

    $(".tableDebits tbody").on("click","button.edit",function()
    {
        var DebitId = $(this).attr("DebitId");
        var route = '/finance/debit/see';
        var token = $('#tokenModal').val();
        var data = new FormData();
        data.append('DebitId', DebitId);
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
                if (response["status"]==1)
                {
                    $('#EditStatus').removeAttr("name");
                    $('#EditStatus').attr("readonly","readonly");
                    $('#EditId_alumno').removeAttr("name");
                    $('#EditId_alumno').attr("readonly","readonly");
                    $('#EditAmount').removeAttr("name");
                    $('#EditAmount').attr("readonly","readonly");
                }  
                else
                {
                    $('#EditStatus').attr("name","EditStatus");
                    $('#EditStatus').removeAttr("readonly");
                    $('#EditId_alumno').attr("name","EditId_alumno");
                    $('#EditId_alumno').removeAttr("readonly");
                    $('#EditAmount').attr("name","EditAmount");
                    $('#EditAmount').removeAttr("readonly");
                }        
                $('#EditConcept').val(response['concept']);
                $('#EditAmount').val(response['amount']);
                $('#EditId_alumno').val(response['alumnId']);
                $('#EditStatus').val(response['status']);
                $('#DebitIdUpdate').val(response['debitId']);
                $('#EditDescription').val(response['description']);           
               
            }
        });
    });

    $(".tableDebits tbody").on("click","button.showPdf",function()
    {
      var route = $(this).attr("route");
      window.open("/"+route,"_blank");
    });

    $(".tableDebits tbody").on("click","button.btnDeleteDebit",function()
    {
        var id = $(this).attr("DebitId");
        swal.fire({
            title: '¿estas seguro de eliminar este adeudo?',
            text: "¡se eliminara de los registros!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Si, estoy seguro'
        }).then((result)=>
        {
            window.location = "/finance/debit/delete/"+id;
        });
    })

    $(".tableDebits tbody").on("click","button.details",function()
    {  
        var DebitId = $(this).attr("DebitId");
        var is = $(this).attr("is");
        var route = '/finance/debit/payment-details';
        var token = $('#tokenModal').val();
        var data = new FormData();
        data.append('DebitId', DebitId);
        data.append('is', is);
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
              
                if (response["type"]=="card")
                {
                  
                    $('#detail-id').text("ID: " +response['id']);
                    $('#detail-paymentMethod').text("Método de pago: " +response['paymentMethod']);
                    $('#detail-reference').text("Sin referencia");
                    $('#detail-amount').text("Monto: " +response['amount']);
                  
                }   
                else
                {
                  
                    $('#detail-id').text("ID: " +response['id']);
                    $('#detail-paymentMethod').text("Método de pago: " +response['paymentMethod']);
                    $('#detail-reference').text("Referencia: " +response['reference']);
                    $('#detail-amount').text("Monto: " +response['amount']);
                   
                }      

                $('#detail-id').show();
                $('#detail-paymentMethod').show();
                $('#detail-reference').show();
                $('#detail-amount').show();     

                           
            }
        });
        $('#loader').show();



        $("#modalShowDetails").on('hidden.bs.modal', function () {
            $('#detail-id').hide();
            $('#detail-paymentMethod').hide();
            $('#detail-reference').hide();
            $('#detail-amount').hide();
        }); 
       
    });
}

$("#id_alumno").select2({
    width: 'resolve'
});

$(document).ready(function(){
    changeMode($("#mode").val());
})

$("#mode").change(function(){
    changeMode($("#mode").val());
})









