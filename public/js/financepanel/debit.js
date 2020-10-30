function changeMode(mode, period, concept){
    var token = $("#token").val();
    var route = "/finance/debit/show";
    $('.tableDebits tbody').remove();
    $(".tableDebits").dataTable({
        "destroy": true,
        "processing": true,
        "responsive": true,
        serverSide: true,
        stateSave: true,
        "ajax":
        {
            url: route,
            headers:{'X-CSRF-TOKEN':token},
            type: "POST",
            data: {mode:mode,period:period,concept:concept}
        },
        "columns":[
            {"data": "#"},
            {"data": null, orderable: false, "render": function(data){

                var res = "<div class='btn-group'>";

                if (data.debit_type_id == 1) {
                    res += "<button class='btn btn-warning btnValidate' data-toggle='modal' data-target='#modalInscripcion' DebitId='"+data.debitId+"' title='Validar'>"+
                    "<i class='fa fa-edit' style='color:white'></i></button>";
                } else {
                    res += "<button class='btn btn-warning edit' data-toggle='modal' data-target='#modalEdit' DebitId='"+data.debitId+"' title='Editar Adeudo'>"+
                    "<i class='fa fa-edit' style='color:white'></i></button>";
                }

                if(data.id_order != null && data.method != "transfer") {
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
        var data = new FormData();
        data.append('DebitId', $(this).attr("DebitId"));
        $.ajax({
            url:'/finance/debit/see',
            headers:{'X-CSRF-TOKEN': $('#tokenUpdate').val()},
            method:'POST',
            data:data,
            cache:false,
            contentType:false,
            processData:false,
            success:function(response)
            {   
                if (response["status"] == 1) {
                    $('#status-edit').bootstrapToggle("on");
                } else {
                    $('#status-edit').bootstrapToggle("off");
                }

                if (response["id_order"] != null && response["method"] == "transfer") {
                    $("#edit-button").attr("route", response["id_order"]);
                    $("#edit-container").show();
                } else {
                    $("#edit-container").hide();
                }
                $('#amount').val(response['amount']);
                $('#id_alumno').prepend("<option value='"+response["alumnId"]+"' selected>"+response["enrollment"] + " "+ response["alumnName"]+"</option>");
                $('#debitId').val(response['debitId']);
                $('#description').val(response['description']);               
            }
        });
    });

    $(".tableDebits tbody").on("click","button.btnValidate",function()
    {
        var data = new FormData();
        data.append('DebitId', $(this).attr("DebitId"));
        $.ajax({
            url: '/finance/debit/see',
            headers:{'X-CSRF-TOKEN': $('#tokenValidate').val()},
            method:'POST',
            data:data,
            cache:false,
            contentType:false,
            processData:false,
            success:function(response)
            {    
                $('#loader-validate').hide();  
                if (response.id_order == null) {

                    var res = "<div class='row'><div class='col-md-12'>El alumno "+response["alumnName"]+" ni ha subido comprobante o realizado un pago</div></div>"; 
                    $("#validate-button").hide();
                } else {

                    if (response.status == 0) {
                        var res = "<p>Antes de validar el pago, asegurece de validar este id en CONEKTA o este link con el comprobante</p>";
                        if (response.method == "transfer") {
                            res += "<p style='text-align: center'><button type='button' class='btn btn-info' id='showPdf' route='"+response.id_order+"' target='_blank'>Ver comproboante</button></p>";
                        } else {
                            res += "<p style='text-align: center'>"+response.id_order+"></p>";
                        }
                        res += "<p>Una vez verificado, puede validar el adeudo activando el toggle de abajo y luego en guardar</p>" +
                        '<input class="toggle-bootstrap" name="verification" type="checkbox" data-width="150"  data-toggle="toggle"' +
                        'data-on="Validado" data-off="Sin validar"  data-onstyle="success" data-offstyle="danger">'+
                        '<input type="hidden" value="'+response.debitId+'" name="debit_id">'+
                        '<script>$(".toggle-bootstrap").bootstrapToggle(); $("#showPdf").click(function(){'+
                        'var route = $(this).attr("route");window.open("/"+route,"_blank");});</script>'; 
                    } else {
                        var res = "<p>Revisar registro de comprobante o id de CONEKTA</p>";
                        if (response.method == "transfer") {
                            res += "<p style='text-align: center'><button type='button' class='btn btn-info' id='showPdf' route='"+response.id_order+"' target='_blank'>Ver comproboante</button></p>";
                        } else {
                            res += "<p style='text-align: center'>"+response.id_order+"></p>";
                        }
                        res += "<div class='row'><div class='col-md-12'>" + 
                          "<p>Este alumno ya fue validado con este adeudo</p>";
                        $("#validate-button").hide();
                    }            
                }
                $("#content-validate").append(res);
            }
        });
        $('#loader-validate').show();
        $("#validate-button").show();
        $("#content-validate").empty()
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
    changeMode($("#mode").val(),$("#period").val(),$("#concept").val());
})

$("#mode").change(function(){
    changeMode($("#mode").val(),$("#period").val(),$("#concept").val());
});

$("#period").change(function(){
    changeMode($("#mode").val(),$("#period").val(),$("#concept").val());
});

$("#concept").change(function(){
    changeMode($("#mode").val(),$("#period").val(),$("#concept").val());
});

$(".toggle-bootstrap").bootstrapToggle();

$(".showPdf").click(function()
{
    var route = $(this).attr("route");
    window.open("/"+route,"_blank");
});









