const op = { style: 'currency', currency: 'USD' };
const nf = new Intl.NumberFormat('en-US', op);

function changeMode(mode, period, concept){

    $('.tableDebits tbody').remove();

    $(".tableDebits").dataTable({
        "destroy": true,
        "processing": true,
        "responsive": true,
        serverSide: true,
        stateSave: true,
        "ajax": {
            url: "/finance/debit/show",
            headers:{'X-CSRF-TOKEN' : $("#token").val()},
            type: "POST",
            data: {mode:mode,period:period,concept:concept}
        },
        "columns":[
            {"data": null, orderable: false, "render": function(data){
                var res = "<div class='btn-group'>";

                if(data.status == 1) {
                    res+="<button class='btn btn-info btnPrintTicket' title='Imprimir ticket' debitId='"+data.id+"'>"+
                    "<i class='fa fa-print'></i></button>";
                }

                res += "<button class='btn btn-primary btnUpload' title='title='Subir comprobante'' DebitId='"+data.id+"'>"+
                    "<i class='fa fa-upload' title='Subir comprobante' style='color:white'></i></button>";

                if (data.debit_type_id == 1) {
                    res += "<button class='btn btn-warning btnValidate' data-toggle='modal' data-target='#modalInscripcion' DebitId='"+data.id+"' title='Validar'>"+
                    "<i class='fa fa-edit' style='color:white'></i></button>";
                } else {
                    res += "<button class='btn btn-warning edit' data-toggle='modal' data-target='#modalEdit' DebitId='"+data.id+"' title='Editar Adeudo'>"+
                    "<i class='fa fa-edit' style='color:white'></i></button>";
                }

                if(data.id_order != null && data.payment_method != "transfer") {
                    res+="<button class='btn btn-danger custom details' data-toggle='modal' data-target='#modalShowDetails' is='"+data.payment_method+"' DebitId='"+data.id+"'>"+
                    "<i class='fa fa-eye' title='Ver detalles del pago' style='color:white'></i></button>";
                }

                res += "<button class='btn btn-danger  btnDeleteDebit' DebitId='"+data.id+"'>"+
                    "<i class='fa fa-times' title='Eliminar adeudo' style='color:white'></i></button></div>"; 
                return res;
            }},
            {"data": null, orderable: false, "render": function(data){
                return data.alumn.Matricula; 
            }},
            {"data": null, orderable: false, "render": function(data){
                return data.alumn.Nombre + " " + data.alumn.ApellidoPrimero + " " + data.alumn.ApellidoSegundo; 
            }},
            {"data": null, orderable: false, "render": function(data){
                return data.alumn.Email; 
            }},
            {"data": null, orderable: false, "render": function(data){
                return data.description; 
            }},
            {"data": null, orderable: false, "render": function(data){
                return nf.format(data.amount); 
            }},
            {"data": null, orderable: false, "render": function(data){
                return data.status == 1 ? "Pagada" : "Pendiente"; 
            }},
            {"data": null, orderable: false, "render": function(data){
                return data.created_at; 
            }},
            {"data": null, orderable: false, "render": function(data){
                return data.alumn.plan_estudio.carrera.Nombre; 
            }},
            {"data": null, orderable: false, "render": function(data){
                return data.alumn.Localidad + ", " +data.alumn.estado.Nombre; 
            }},
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
                $('.loader-modal').hide();  
                if (response.id_order == null) {

                    var res = "<div class='row'><div class='col-md-12'>El alumno "+response["alumnName"]+" ni ha subido comprobante o realizado un pago</div></div>"; 
                    $("#validate-button").hide();
                } else {

                    if (response.status == 0) {
                        var res = "<p>Antes de validar el pago, asegurece de validar este id en CONEKTA o este link con el comprobante</p>";
                        if (response.method == "transfer") {
                            res += "<p style='text-align: center'><button type='button' class='btn btn-info showPdf' route='"+response.id_order+"'>Ver comproboante</button></p>";
                        } else {
                            res += "<p style='text-align: center'>"+response.id_order+"></p>";
                        }
                        res += "<p>Una vez verificado, puede validar el adeudo activando el toggle de abajo y luego en guardar</p>" +
                        '<input class="toggle-bootstrap" name="verification" type="checkbox" data-width="150"  data-toggle="toggle"' +
                        'data-on="Validado" data-off="Sin validar"  data-onstyle="success" data-offstyle="danger">'+
                        '<input type="hidden" value="'+response.debitId+'" name="debit_id">'+
                        '<script>$(".toggle-bootstrap").bootstrapToggle();</script>'; 
                    } else {
                        var res = "<p>Revisar registro de comprobante o id de CONEKTA</p>";
                        if (response.method == "transfer") {
                            res += "<p style='text-align: center'><button type='button' class='btn btn-info showPdf' route='"+response.id_order+"'>Ver comproboante</button></p>";
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
        $('.loader-modal').show();
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
            if(result.value){
                window.location = "/finance/debit/delete/"+id;
            }
        });
    })

    $(".tableDebits tbody").on("click","button.btnUpload",function()
    {
        var id = $(this).attr("DebitId");
        $("#debit_id_upload").val(id);
        $("#modalUpload").modal("show");
    })

    $(".tableDebits tbody").on("click","button.details",function()
    {  
        var DebitId = $(this).attr("DebitId");
        var is = $(this).attr("is");

        var data = new FormData();
        data.append('DebitId', DebitId);
        data.append('is', is);

        $.ajax({
            url:'/finance/debit/payment-details',
            headers:{'X-CSRF-TOKEN': $('#tokenModal').val()},
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

    $(document).on("click", "button.btnPrintTicket", function() {
        var debitId = $(this).attr("debitId");
        $.get('/finance/tickets/get?debitId='+debitId, function(data) {
            if(data.status == "success") {
                window.open("/"+data.data.route,"_blank");
            }
        });
    });
}

$("#id_alumno").select2({
    ajax: {
        url: "/finance/debit/search-alumn",
        dataType: 'json',
        data: function (params) {
            return {
                filter: params.term, // search term
            };
        },
        processResults: function (data,params) {
            return {
                results: data.results, // search term
            };
        },
        cache: true
    },
    placeholder: 'Buscar alumno',
    minimumInputLength: 3,
    width: 'resolve',
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

$(document).on("click", "button.showPdf", function()
{
    var route = $(this).attr("route");
    window.open("/"+route,"_blank");
});

$(function () {
    $('[data-toggle="modal"]').tooltip()
});

$("#generate-excel").click(function() {

    loader(true, "cargando datos (esto puede demorar)");

    data = {
        "_token": $("#token-excel").val(),
        "period_id": $("#period_id").val(),
        "is_paid": $("#is_paid").val(),
        "initial_date": $("#initial_date").val(),
        "end_date": $("#end_date").val()
    };

    $.post("/finance/generate-excel", data, function(response) {
        loader(true, "Creando excel");
        setTimeout(function() {
            createNewXLSX(response, data);
        }, 500);
    });
});


function createNewXLSX(data, filters) {

    var title = "Reporte de adeudos";

    var wb = XLSX.utils.book_new();

    wb.Props = {
        Title: title,
        Author: "Universidad de la sierra",
    };

    wb.SheetNames.push("Adeudos");

    var correct = getFormatDebit(data, filters);

    var correctData = XLSX.utils.aoa_to_sheet(correct);

    wb.Sheets["Adeudos"] = correctData;

    var wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});

    saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), title+".xlsx");
    loader(false, "");
}

var headers = ["Matricula", "Descripción del Adeudo", "Importe", "Estado del Adeudo", "Fecha del Pago", "Nombre del Alumno", "Email", "Localidad", "Estado", "Carrera"];

function getFormatDebit(data, filters) {
    var correctData = [];
    correctData.push(["Universidad de la Sierra"]);

    correctData.push(["Departamento de Recursos Financieros"]);

    correctData.push([]);
    correctData.push(["Reporte de Adeudos"]);
    correctData.push(["Filtros"]);

    if (filters.period_id != "") {
        correctData.push(["Periodo: ", filters.period_id]);
    }

    if (filters.initial_date != "") {
        correctData.push(["Fecha de inicio: ", filters.initial_date]);
    }

    if (filters.end_date != "") {
        correctData.push(["Fecha final: ", filters.end_date]);
    }

    if (filters.is_paid != "") {
        correctData.push(["Estado: ", filters.is_paid == 1 ? "Pagados" : "Pendientes"]);
    }
    correctData.push([]);
    correctData.push(headers);

    for (var i = 0; i < data.length; i++) {
        var date = data[i].updated_at;
        correctData.push([
            data[i].alumn.Matricula ? data[i].alumn.Matricula : "no matricula",
            data[i].description,
            nf.format(data[i].amount),
            data[i].status == 1 ? "Pagado" : "Pendiente",
            date.substring(0, 10),
            data[i].alumn.FullName,
            data[i].alumn.Email,
            data[i].alumn.Localidad,
            data[i].alumn.estado.Nombre,
            data[i].alumn.plan_estudio.Nombre
        ]);
    }
    return correctData;
}

function s2ab(s) { 
    var buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
    var view = new Uint8Array(buf);  //create uint8array as viewer
    for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
    return buf;    
}

function loader(flag, text) {
    if (flag) {
        $(".body-loader-load").fadeIn(500);
    } else {
        $(".body-loader-load").fadeOut(500);
    }
    $(".loader-body-text").text(text);
}









