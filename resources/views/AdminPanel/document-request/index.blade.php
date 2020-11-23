@extends('AdminPanel.main')

@section('content-admin')

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Solicitudes <small>de documentos</small></h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active"><a href="#">Problemas</a></li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">

    <div class="card">

      <div class="card-body">

        <table class="table table-bordered table-hover tableRequest">

          <thead>

            <tr>
              <th>Alumno</th>
              <th>Email</th>
              <th>Documento</th>
              <th>Descripcion</th>
              <th>Periodo</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>

          </thead>
          <tbody>
            @foreach($documents as $key => $value)
            <tr>
              <td>{{$value->alumn->name ." ". $value->alumn->lastname ? $value->alumn->lastname : ''}}</td>
              <td>{{ $value->alumn->email }}</td>
              <td>{{ $value->documentType->name }}</td>
              <td>{{ $value->description }}</td>
              <td>{{ $value->period->clave}}</td>
              <td>{{ $value->payment == 0 ? 'Sin realizar pago' : 'Pagado' }}</td>
              <th>
                  <div class="btn-group">
                    <button class="btn btn-info btnUpload" documentId="{{$value->id}}" data-toggle="modal" data-target="#modal" title="Cargar"><i class="fa fa-upload"></i></button>
                    <button class="btn btn-success btnRequest" documentId="{{$value->id}}" title="Validar"><i class="fa fa-th"></i></button>
                  </div>
              </th>
            </tr>
            @endforeach
          </tbody>

        </table>

      </div>

    </div>

  </section>

</div>

<div class="modal fade" id="modal">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header">

        <h3>Cargar documento</h3>

      </div>
      <form action="{{route('admin.document.request.upload')}}" method="post" enctype="multipart/form-data">
        <div class="modal-body">
              
            {{ csrf_field() }}

            <div class="row">

              <div class="col-md-12">
                
                <div class="form-group">

                <div class="panel">SUBIR DOCUMENTO</div>

                  <input type="hidden" name="idDocument" id="idDocument">

                  <input type="file" name="document" id="document" accept="application/pdf"  required>

                </div>

              </div>

            </div>

        </div>

        <div class="modal-footer">
          
          <div class="row" >

            <div class="col-md-12">

              <div class="form-group" id="pay-now">

                <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>

                <button class="btn btn-success" type="submit">subir</button>
                
              </div>

            </div>

          </div>

        </div>
      </form>

    </div>

  </div>

</div>
<script>
  $(".tableRequest").DataTable({
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ registros",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
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
    },
    "buttons": {
        "copy": "Copiar",
        "colvis": "Visibilidad"
    }
});

  $(".tableRequest tbody").on("click","button.btnUpload",function()
  {
      $("#idDocument").val($(this).attr("documentId"));
  });

  $(".tableRequest tbody").on("click","button.btnRequest",function()
  {
      var requestId = $(this).attr("documentId");
      swal.fire({
          title: '¿Ya entregó el documento?',
          text: "¡Antes de cambiar de estado!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'Cancelar',
          confirmButtonText: 'Si'
      }).then((result)=>
      {
        if (result.value)
        {
          window.location = "/admin/document/request/fix"+requestId
        }
      });
  });

  $('#document').change(function()
  {
    var file = this.files[0];
    var ext = file['type'];
    console.log(ext);
    if ($(this).val() != '') 
    {
      if(ext == "application/pdf")
      {
      if(file["size"] > 10485769)
      {
        toastr.error("Se solicita un archivo no mayor a 1MB. Por favor verifica.");
        $(this).val('');
      }
      else
      {
        toastr.success("Formato permitido");
      }
      }
      else
      {
      $(this).val('');
      toastr.error("Extensión no permitida: " + ext);
      }
    }
  });
</script>

@stop
