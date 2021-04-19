@extends('DepartamentPanel.main')

@section('content-departament')

<div class="content-wrapper">

  <section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1>Registro de aulas</h1>

        </div>

        <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right">

            <li class="breadcrumb-item active"><a href="#">Home</a></li>

          </ol>

        </div>

      </div>

    </div>

  </section>

  <section class="content">

    <div class="card">

      <div class="card-header">
        <div class="row">
          <div class="col-md-12">
            <a href="{{ route('departament.logs.classrooms.create') }}" class="btn btn-success"><i class="fa fa-plus"></i></a>
          </div>
        </div>
      </div>
      
      <div class="card-body">

        <table class="table table-bordered table-hover dt-responsive">

          <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Area</th>
              <th>Nombre</th>
              <th>Codigo</th>
              <th>Numero</th>
              <th>Estado</th>
              <th>Acciones</th>             
            </tr>  
          </thead>

          <tbody>
            
            @foreach($instances as $key => $item)
            <tr>
              <td>{{ ($key+1)}} </td>
              <td>{{ $item->area->name }}</td>
              <td>{{ $item->name }}</td>
              <td>{{ $item->code }}</td>
              <td>{{ $item->num }}</td>
              <td>{{ $item->state == 0 ? 'disponible' : 'Ocupada/mantenimiento' }}</td>
              <td>
                <div class="btn-group">
                  <a class="btn btn-warning" href="{{ route('departament.logs.classrooms.edit', $item->id) }}">
                    <i class="fa fa-edit" style="color: white"></i>
                  </a>
                  <button class="btn btn-danger btnDelete" idClassroom="{{$item->id}}">
                    <i class="fa fa-times"></i>
                  </button>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>

        </table>

      </div>

    </div>

  </section>

</div>

<script>
  $(".table").DataTable({
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

  $(".btnDelete").click(function() {
    var id = $(this).attr("idClassroom");
    swal.fire({
        title: '¿Seguro que deseas borrar este salon?',
        text: "¡Si lo haces todos los equipos ligados con este salon serán borrados!",
        type: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, Borrar'
    }).then((result)=>
    {
        if (result.value)
        {
          window.location = "/departaments/logs/classrooms/delete/"+id;
        }
    });
  });
</script>

@stop
