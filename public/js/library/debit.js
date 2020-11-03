function changeMode(mode, period, concept){
    $('.tableDebits tbody').remove();
    $(".tableDebits").dataTable({
        "destroy": true,
        "processing": true,
        "responsive": true,
        serverSide: true,
        stateSave: true,
        "ajax":
        {
            url: "/library/debit/show",
            headers:{'X-CSRF-TOKEN':$("#token").val()},
            type: "POST",
            data: {mode:mode,period:period,concept:concept}
        },
        "columns":[
            {"data": "#"},
            {"data": null, orderable: false, "render": function(data){

                var res = "<div class='btn-group'>";

                if (data.Estado == "Pendiente") {
                    res += "<button class='btn btn-warning edit' data-toggle='modal' data-target='#modalEdit' DebitId='"+data.debitId+"' title='Editar Adeudo'>"+
                    "<i class='fa fa-edit' style='color:white'></i></button><button class='btn btn-danger btnDeleteDebit' DebitId='"+data.debitId+"' title='Editar Adeudo'>"+
                    "<i class='fa fa-times' style='color:white'></i></button>";
                } else {
                    res += "<button class='btn btn-default'>"+
                    "Disabled</button>";
                }
                res+="</div>";
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
            url:'/library/debit/see',
            headers:{'X-CSRF-TOKEN': $('#tokenUpdate').val()},
            method:'POST',
            data:data,
            cache:false,
            contentType:false,
            processData:false,
            success:function(response)
            {   
                $('#amount').val(response['amount']);
                $('#debitId').val(response['debitId']);
                $('#description').val(response['description']);               
            }
        });
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
            window.location = "/library/debit/delete/"+id;
        });
    })
}

$("#id_alumno").select2({
    width: 'resolve'
});

$(".select2").select2({
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