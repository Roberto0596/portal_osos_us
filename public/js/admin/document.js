
function loadDatatable(){ 
    var token = $("#token").val();
  var route = "/admin/documents/show";
  $('.tableDocuments tbody').remove();
  $(".tableDocuments").dataTable({
      "destroy": true,
      "responsive": true,
      "ajax":
      {
          url: route,
          headers:{'X-CSRF-TOKEN':token},
          type: "PUT",
      },
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
  
       console.log("hola");
        var files = $(this).attr("files");
        var decodedData = JSON.parse(files);
  
        
  
        decodedData.forEach( file => {
  
          $("#" + file["document_type_id"]).attr("disabled", false);
  
          $("#switch" + file["document_type_id"]).attr("disabled", false);
  
          if(file["status"]  === 2   || file["status"]  == 2){
            $("#switch" + file["document_type_id"]).bootstrapToggle('on')
          }
  
         
          $("#" + file["document_type_id"]).click(function(){
            window.open(file["route"],"_blank");
          });
  
          $("#switch" + file["document_type_id"]).attr("document", file["id"]);
          $("#switch" + file["document_type_id"]).attr("status", file["status"]);
  
  
      });
  
          
        
  });
  
  }
  
  
  
  $(".switch").change(function(){  
  
    var document_id = $(this).attr("document");
    
      if( document_id  != undefined){
            var route = '/admin/document/update-status';
            var status = $(this).attr("status");
  
            // console.log(status + "status");
            var document_id = $(this).attr("document");
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
            $(this).attr("document",undefined);
        }
              });
    
  
  $(document).ready(function(){
    loadDatatable();
  })
  
  $("#modalDocuments").on('hidden.bs.modal', function () {
  loadDatatable();
  
    
   // table.draw("full-reset");
  
  });