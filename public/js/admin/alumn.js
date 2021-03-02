var token = $("#token").val();
var route = "/admin/alumns/show";

$(".tableAlumns").dataTable({
    "destroy": true,
    "responsive": true,
    "ajax":
    {
        url: route,
        headers:{'X-CSRF-TOKEN':token},
        type: "POST",
    },
    serverSide:true,
    "columns":[
        {"data": "#"},
        {"data": "Matricula"},
        {"data": "Nombre (s)"},
        {"data": "Apellido (s)"},
        {"data": "Email"},
        {"data": null, orderable: false, "render": function(data){
            var res = "";
            switch (data.inscripcion) {
                case 0:
                    res = "Sin llenar formulario";
                    break;
                case 1:
                    res = "Sin realizar el pago";
                    break;
                case 2:
                    res = "Esperando confirmación de pago";
                    break;
                case 3:
                    res = "Proceso terminado";
                    break;
                case 4:
                    res = "Carga asignada";
                    break;
            } 
            return res;
        }},
        {"data": null, orderable: false, "render": function(data){

            var res = "<div class='btn-group'>";
            if (data.validate) {
                res += "<button class='btn btn-warning btnUpdateAlumn' data-toggle='modal' data-target='#modal-edit-alumn' title='editar alumno' alumnId = '"+data.id+"' title='Imprimir'>"+
                    "<i class='fa fa-pen' style='color:white'></i></button>";
            }
            res+= "<button class='btn btn-danger btnDeleteAlumn' title='Eliminar alumno' alumnId = '"+data.id+"' title='Imprimir'>"+
            "<i class='fa fa-times'></i></button></div>";
            return res;
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

$(".tableAlumns tbody").on("click","button.btnDeleteAlumn",function(){
    var id = $(this).attr("alumnId");
    swal.fire({
        title: '¿Esta seguro de eliminar este alumno?',
        text: "¡todo registro de el sera borrado!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, estoy seguro'
    }).then((result)=>{
      if (result.value)
      {
        window.location = "/admin/alumns/delete/"+id;        
      }
    });
});

$(".tableAlumns tbody").on("click","button.btnUpdateAlumn",function(){
    var id = $(this).attr("alumnId");
    $('#id_alumn').val(id);
    var route = '/admin/alumns/alumnDada';
    var token = $('#token2').val();
    var data = new FormData();
    data.append('id', id);
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
            console.log(response['inscription_status']);   
            $('#PlanEstudioActual').val(response['PlanEstudio']);
            $('#EncGrupoActual').val(response['group']);
            $('#matriculaActual').val(response['enrollment']);
            $('#semestre').val(response['semestre']);
            $("#inscription-status option[value="+ response['inscription_status'] +"]").attr("selected",true);
        }
    });
});

$('#PlanEstudioId').change(function(){
    var planEstudio = $(this).val();
    var route = '/admin/alumns/generateEnrollment';
    var token = $('#token2').val();
    var data = new FormData();
    data.append('planEstudio', planEstudio);
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
            $('#matriculaGenerada').val(response);
        }
    });
});

$('#inscription-status').change(function(){

    if ($(this).val() == 1) {
        swal.fire({
            title: '¿Quiere que generemos el adeudo correspondiente?',
            text: "¡si acepta, se creara un adeudo de inscripcion para este alumno!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Si, por favor'
        }).then((result)=>{
          if (result.value)
          {
            $('#is_payment').val(1);
          }
        });
    }

});