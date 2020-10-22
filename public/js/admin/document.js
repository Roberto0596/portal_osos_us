function loadDatatable() { 
  var token = $("#token").val();
  var route = "/admin/documents/show";
  $('.tableDocuments tbody').remove();
  let table = $(".tableDocuments").DataTable({
      "destroy": true,
      "processing":true,
      "responsive": true,
      stateSave: true,
      "ajax":
      {
          url: route,
          headers:{'X-CSRF-TOKEN':token},
          type: "POST",
      },
      serverSide: true,
      "columns":[
              {"data": "#"},
              {"data": "Matricula"},
              {"data": "Alumno"},
              {"data": null, orderable: false, "render": function(data){
  
  
                if(data.count === 5){
  
                  let statusButton = "<button type='submit' class='btn btn-success'>"+
                  "<i class='fa fa-check-circle' title='Completado'></i></button> &nbsp&nbsp&nbsp "+data.count + " / 5 Validados";
  
                  return statusButton;
  
                }else{
  
                  let statusButton = "<button type='submit' class='btn btn-danger custom'>"+
                  "<i class='fa fa-exclamation-circle' title='Sin Validar'></i></button> &nbsp&nbsp&nbsp " + data.count +" / 5 Validados";
                  return statusButton;
                }
                 
              }},
              {"data": null, orderable: false, "render": function(data){
                let showFiles =  "<button class='btn btn-warning custom ShowFiles' files='" + data.files +
                "'data-toggle='modal'  data-target='#modalDocuments' title='Ver documentos'><i class='fa fa-eye'></i>"+
               "&nbsp&nbsp Ver </button></div>  &nbsp&nbsp&nbsp "+data.countFiles+" / 5 Subidos";
  
               return showFiles;
                 
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
    
  $(".tableDocuments tbody").on("click","button.ShowFiles",function()
  {
      $("#tbody-documents").empty()
      var files = $(this).attr("files");
      var decodedData = JSON.parse(files);      
      var collection = "";
      decodedData.forEach( file => {
        var res = '<tr><td>'+file.name+'</td>'+
          '<td><a href="'+file.route+'" target="_blank" class="btn btn-danger custom">';
        if (file.status  === 2   || file.status == 2) {
          res += '<i class="fa fa-file" title="Probado"></i></a>';
        } else {
          res += '<i class="fa fa-file" title="No Probado"></i></a>';
        }
        res += '</td><td>';
        if (file.status  === 2   || file.status == 2) {
          res += '<input class="toggle-bootstrap" type="checkbox" data-width="150"  data-toggle="toggle" data-on="Aprobado" data-off="Sin Aprobar" checked data-onstyle="success" data-offstyle="default" document = "'+file.id+'" status = "'+file.status+'">';
        } else {
          res += '<input class="toggle-bootstrap" type="checkbox" data-width="150"  data-toggle="toggle" data-on="Aprobado" data-off="Sin Aprobar" data-onstyle="success" data-offstyle="default" document = "'+file.id+'" status = "'+file.status+'"';
        }            
        res += '</td></tr>';
        collection += res;
    }); 
    $("#tbody-documents").append(collection);
    $(".toggle-bootstrap").bootstrapToggle(); 
    $(".toggle-bootstrap").change(function(){  
      var document_id = $(this).attr("document");    
      var route = '/admin/document/update-status';
      var status = $(this).attr("status");
      var token = $('#tokenModal').val();
      var data = new FormData();
      let value = (status == 1 ) ? 2 : 1;

      data.append('document_id', document_id);
      data.append('value', value);

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
          if(response === 'ok'){
            Swal.fire(
              'Listo!',
              'Estado de documento actualizado',
              'success',
            );
          }else{
            Swal.fire(
              'Error!',
              'No se pudo actualizar el estado del documento',
              'error',
            );
          }
        }                 
      });
    }); 
  });
  table.on('click', 'tr', function () {
    var data = table.row(this).data();
    console.log(data);  
  });  
}
    
  
$(document).ready(function(){
    loadDatatable();
});
  
$("#modalDocuments").on('hidden.bs.modal', function () {
  loadDatatable();  
});