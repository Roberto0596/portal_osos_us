const op = { style: 'currency', currency: 'USD' };
const nf = new Intl.NumberFormat('en-US', op);

$(document).ready(function() {

    const filters = {
        status: null,
        period: null,
        concept: null,
        payment_method: null,
        init: function() {

            $("#mode").change(function() {            
                filters.status = $("#mode").val();
                localStorage.setItem("status", filters.status);
                Datatable.dataTable.draw();
            });

            $("#period").change(function() {            
                filters.period = $("#period").val();
                localStorage.setItem("period", filters.period);
                Datatable.dataTable.draw();
            });

            $("#concept").change(function() {            
                filters.concept = $("#concept").val();
                localStorage.setItem("concept", filters.concept);
                Datatable.dataTable.draw();
            });

            $("#payment_method").change(function() {
                filters.payment_method = $("#payment_method").val();
                localStorage.setItem("payment_method", filters.payment_method);
                Datatable.dataTable.draw();
            });

            var payment_method = localStorage.getItem("payment_method");
            var concept = localStorage.getItem("concept");
            var period = localStorage.getItem("period");
            var status = localStorage.getItem("status");

            $("#payment_method option[value="+ payment_method +"]").attr("selected",true);
            $("#concept option[value="+ concept +"]").attr("selected",true);
            $("#period option[value="+ period +"]").attr("selected",true);
            $("#mode option[value="+ status +"]").attr("selected",true);

            filters.payment_method = $("#payment_method").val();
            filters.concept = $("#concept").val();
            filters.period = $("#period").val();
            filters.status = $("#mode").val();
        }
    };

    var Datatable = {
        table: $(".tableDebits"),
        init: () => {
            Datatable.dataTable = Datatable.table.DataTable({
                "destroy": true,
                "processing": true,
                "responsive": true,
                "serverSide": true,
                "stateSave": true,
                "ajax": {
                    "url": "/finance/debit/datatable",
                    "headers":{'X-CSRF-TOKEN' : $("#token").val()},
                    "type": "POST",
                    "data": {
                        "status": function() {
                            return filters.status;
                        },
                        "period": function() {
                            return filters.period;
                        },
                        "concept": function() {
                            return filters.concept;
                        },
                        "payment_method": function() {
                            return filters.payment_method;
                        }
                    }
                },
                "columns":[
                    {"data": "enrollment", "orderable": true},
                    {"data": null, "orderable": false, "render": function(data){
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

                        if(data.id_order != null && data.payment_method != "transfer" && data.id_order != 0) {
                            res+="<button class='btn btn-danger custom details' data-toggle='modal' data-target='#modalShowDetails' is='"+data.payment_method+"' DebitId='"+data.id+"'>"+
                            "<i class='fa fa-eye' title='Ver detalles del pago' style='color:white'></i></button>";
                        }

                        res += "<button class='btn btn-danger  btnDeleteDebit' DebitId='"+data.id+"'>"+
                            "<i class='fa fa-times' title='Eliminar adeudo' style='color:white'></i></button></div>"; 
                        return res;
                    }},
                    {"data": "alumnName"},
                    {"data": "description", "orderable": true},
                    {"data": "convertMethod", "orderable": true},
                    {"data": "amount", "orderable": true, "render": function(data){
                        return nf.format(data); 
                    }},
                    {"data": "convertStatus", "orderable": true},
                    {"data": "payment_date", "orderable": true, "render": (data) => {
                        return data ? moment(data).format("Y-MM-D h:m:s") : '';
                    }},
                    {"data": "created_at", "orderable": true, "render": (data) => {
                        return moment(data).format("Y-MM-D h:m:s");
                    }},
                    {"data": "career", "orderable": true },
                    {"data": "location", "orderable": true},
                    {"data": "state", "orderable": true},
                ],
                "language": datatableSpanish
            });
        }
    };

    filters.init();
    Datatable.init();

});

$(document).on("click","button.edit",function()
{
    var debit_id = $(this).attr("DebitId");

    $.ajax({
        url: "/finance/debit/see",
        headers: {'X-CSRF-TOKEN' : $('#tokenUpdate').val()},
        data: {debit_id: debit_id},
        method: 'POST'
    }).then((response) => {
        if (response.id_order != null && response.payment_method == "transfer") {
            $("#edit-button").attr("route", response.id_order);
            $("#edit-container").show();
        } else {
            $("#edit-container").hide();
        }

        $("#editar_status_adeudo option[value='"+response.status+"']").attr("selected", true);
        $("#alumnName").text(response.FullName);
        $("#hidden_id_alumno").val(response.id_alumno);
        $('#amount').val(response.amount);
        $('#debitId').val(response.id);
        $('#description').val(response.description); 
    });
});

$(document).on("click","button.btnValidate", function() {    
    var debit_id = $(this).attr("DebitId");
    $.ajax({
        url: "/finance/debit/see",
        headers: {'X-CSRF-TOKEN' : $('#tokenUpdate').val()},
        data: {debit_id: debit_id},
        method: 'POST'
    }).then((response) => {

        $('.loader-modal').hide();  

        if (response.id_order == null) {

            $("#alumn_name_step_1").text(response.alumn.FullName);
            $("#step1").show();

        } else {

            $("#validate_debit_id").val(response.id);

            if (response.payment_method == "transfer") {
                $(".receipt").append("<p style='text-align: center'><button type='button' class='btn btn-info showPdf' route='"+response.id_order+"'>Ver comproboante</button></p>");
            } else {
                $(".receipt").append("<p style='text-align: center'>"+response.id_order+"></p>");
            }

            $("#step2").show();

            if (response.status == 0 || response.status == 1) {
                $("#verificacion_adedudo option[value='"+response.status+"']").attr("selected", true);
                $("#step-validation").show();
            } else if(response.status == 3) {
                $("#validate-button").hide();
                $("#step-finally").show();
            }           
        }
    });

    $('.loader-modal').show();
});

$(document).on("click","button.btnDeleteDebit", function() {
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
});

$(document).on("click","button.btnUpload", function() {
    $("#debit_id_upload").val($(this).attr("DebitId"));
    $("#modalUpload").modal("show");
});

$(document).on("click","button.details",function() {  
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
        } else {
            swal.fire({
                "type": "error",
                "title": "No se pudo generar el recibo",
                "text": "no fue posible genera el recibo de este adeudo"
            });
        }
    });
});

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

$("#select_alumno_id").select2({
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
        "dates": $("#excel-date-range").val()
    };

    $.post("/finance/generate-excel", data)
    .then((response) => {
        loader(true, "Creando excel");
        setTimeout(function() {
            createNewXLSX(response, data);
        }, 500);
    })
    .catch(() => {
        loader(false, "No se pudo generar el excel");
        swal.fire({
            "title": "Fallo el generar excel",
            "text": "No pudo completarse la carga de los datos",
            "type": "error"
        });
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

    if (filters.dates != "") {
        correctData.push(["Rango de fechas: ", filters.initial_date]);
    }

    if (filters.is_paid != "") {
        correctData.push(["Estado: ", filters.is_paid == 1 ? "Pagados" : "Pendientes"]);
    }
    
    correctData.push([]);
    correctData.push(headers);

    for (var i = 0; i < data.length; i++) {
        var date = data[i].updated_at;
        correctData.push([
            data[i].alumn ? data[i].alumn.Matricula : "no info",
            data[i].description,
            nf.format(data[i].amount),
            data[i].status == 1 ? "Pagado" : "Pendiente",
            date.substring(0, 10),
            data[i].alumn ? data[i].alumn.FullName : "no info",
            data[i].alumn ? data[i].alumn.Email : "no info",
            data[i].alumn ? data[i].alumn.Localidad : "no info",
            data[i].alumn ? data[i].alumn.estado.Nombre : "no info",
            data[i].alumn ? data[i].alumn.plan_estudio.Nombre : "no info"
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

$("#showSelectAlumno").click(function() {
    $(".selectAlumno").show();
});


$("#select_alumno_id").change(function() {
    var valor = $(this).val();
    $("#hidden_id_alumno").val(valor);
});