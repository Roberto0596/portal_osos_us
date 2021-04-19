$(".tableUsers").dataTable({
    "destroy": true,
    "responsive": true,
    "serverSide":true,
    "ajax": {
        url: "/admin/users/show",
        headers:{'X-CSRF-TOKEN':$("#token").val()},
        type: "POST",
    },
    "columns":[
        {"data": null, orderable: false, "render": function(data){
            return data.name;
        }},
        {"data": null, orderable: false, "render": function(data){
            return data.lastname;
        }},
        {"data": null, orderable: false, "render": function(data){
            return data.email;
        }},
        {"data": null, orderable: false, "render": function(data){
            var res = '<img src="/'+data.photo+'" style="width:70px">';
            return res;
        }},
        {"data": null, orderable: false, "render": function(data){
            return data.area.name;
        }},
        {"data": null, orderable: false, "render": function(data){
            return data.created_at;
        }},
        {"data": null, orderable: false, "render": function(data){
            var res = "<div class='btn-group'><a href='/admin/users/edit/user/"+data.id+"'class='btn btn-warning'><i class='fa fa-eye' style='color:white'></i></a><button class='btn btn-danger btnDelete' user_id='"+data.id+"'><i class='fa fa-times'></i></button>";
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

$(".tableUsers tbody").on("click","button.btnDelete",function()
{
    var id = $(this).attr("user_id");
    swal.fire({
        title: '¿Esta seguro de eliminar este usuario?',
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
         window.location = "/admin/users/delete/"+id;        
      }
    });
});


$(".photo").change(function()
{
    var imagen = this.files[0];
    if (imagen["type"] != "image/jpeg" && imagen["type"] != "image/png") 
    {
        $(".newPicture").val(""); 

        swal.fire({
            title: "error al subir foto",
            text: "¡la imagen debe ser en formato JPG o PNG!",
            type: "error",
            conmfirmButtonText:"¡cerrar!"});
    }
    else 
    if(imagen["size"] > 2000000)
    {
        $(".newPicture").val(""); 
        swal.fire({
            title: "Error al subir la imagen",
            text: "la imagen no debe pesar mas de 2MB",
            type: "error",
            conmfirmButtonText: "¡Cerrar!"});
    }
    else
    {
        var datosImagen = new FileReader;
        datosImagen.readAsDataURL(imagen);
        $(datosImagen).on("load", function(event)
        {
            var rutaImagen = event.target.result;
            $(".preview").attr("src", rutaImagen);
        })
    }
})