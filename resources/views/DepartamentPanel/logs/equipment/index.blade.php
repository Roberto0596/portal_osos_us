@extends('DepartamentPanel.main')

@section('content-departament')

<style>
  .tab-content>.active {
    display: block !important;
}
</style>

<link rel="stylesheet" href="{{ asset('css/panel_computer_log.css') }}">

<div class="content-wrapper">

  <section class="content" style="margin-top: 1rem;">

    <div class="card">

      <div class="card-header">

        <div class="row">

          <div class="col-md-4">

            <div class="flex-container">

              <button class="btn btn-success float-right" data-toggle="modal" data-target="#modal-create"><i class="fa fa-plus"></i></button>

            </div>

          </div>

          <div class="col-md-8">

            <div class="float-right">

              <ul class="nav nav-tabs" id="myTab" role="tablist">
                @foreach($classrooms as $key => $item)
                  <li class="nav-item">                                
                    <a class="nav-link @if($key == 0) active @endif changetab" id="{{$item->code}}" data-toggle="tab" href="#{{str_replace(' ', '_', $item->name)}}" role="tab" aria-controls="{{$item->num}}-tab" num="{{$item->num}}">{{ $item->name }}</a>
                  </li>
                @endforeach
              </ul>

            </div>

          </div>

        </div>

      </div>

      <div class="card-body">

        <div class="classroom-body">

          <div class="scroll">

            <div class="tab-content" id="myTabContent">

              @foreach($classrooms as $key => $item)

              <div class="tab-pane fade show @if($key == 0)  active @endif" id="{{str_replace(' ', '_', $item->name)}}" role="tabpanel" aria-labelledby="{{$item->num}}-tab">

                <div class="row">

                    @foreach($item->getEquipments() as $value)

                      <div class="col-md-2">

                          <div class="item-equipment">
                              <button class="btn btn-primary takeit"
                              equipmentId="{{$value->id}}"
                              num="{{$value->num}}"
                              code="{{$value->code}}"
                              >{{$value->code}}</button>
                          </div>

                      </div>

                    @endforeach

                </div>

              </div>

              @endforeach
            </div>

          </div>

        </div>

      </div> 

    </div>

  </section>

</div>

<div class="modal fade" id="modal-create" data-backdrop='static' data-keyboard=false>

  <div class="modal-dialog modal-sm">

    <div class="modal-content">

      <div class="modal-header text-center">

        <h4 class="center">Añadir un nuevo equipo</h4>

      </div>

      <form action="{{ route('departament.logs.equipment.save') }}" method="post">

        {{ csrf_field() }}

        <div class="modal-body">

          <div class="row">

            <div class="col-md-12">

              <label for="">Llene el formulario</label>

              <div class="input-group mb-2">

                <select name="classroom_id" id="classroom_id" class="form-control" required>
                  <option value="">Seleccione un aula </option>
                  @foreach($classrooms as $key => $item)
                  <option value="{{ $item->id }}">{{ $item->name}}</option>
                  @endforeach
                </select>

              </div>

            </div>

            <div class="col-md-12">

              <label for="">Numero</label>

              <div class="input-group mb-2">

                <input type="text" class="form-control" id="num" name="num" required placeholder="Ingrese un numero">

              </div>

            </div>

            <div class="col-md-12">

              <label for="">Estado del equipo</label>

              <div class="input-group mb-2">

                <select name="status" id="status" class="form-control" required>
                  <option value="0">Disponible</option>
                  <option value="1">Ocupado</option>
                  <option value="2">En mantenimiento</option>
                  <option value="3">Fuera de servicio</option>
                </select>

              </div>

            </div>

          </div>
              
        </div>

        <div class="modal-footer">
            <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-default w-100" type="button" data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-success float-right w-100" type="submit" id="accept">Aceptar</button>
                </div>
            </div>
        </div>

      </form>

    </div>

  </div>

</div>

<div class="modal fade" id="modal-quipment-info">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="loader-modal">
        <div class="loader-spinner">Loading...</div>
      </div>

      <div class="modal-header text-center">

        <h4 class="center">Equipo <span id="title-modal"></span></h4>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>      

      <div class="modal-body">

        <div class="row" id="panel-one-modal">
          
          <div class="col-md-6">
            <form action="{{ route('departament.logs.equipment.fill') }}" method="post">
              {{ csrf_field() }}
              <input type="hidden" id="id_equipment" name="id_equipment">
              <button class="btn btn-success option">Bloquear/desbloquear</button>
            </form>

          </div>

          <div class="col-md-6">
            
              <button class="btn btn-primary option" id="alumn-info" disabled>Ver información de alumno logueado</button>
            
          </div>

          <div class="col-md-6">
            <button class="btn btn-warning option" id="change-status">Cambiar estado del equipo</button>
          </div>

          <div class="col-md-6">
            <button class="btn btn-danger option" id="delete-equipment">Eliminar equipo</button>
          </div>

        </div>

        <form action="{{ route('departament.logs.equipment.fill') }}" method="post" id="form-change-status">

          <div class="row" id="panel-two-modal" style="display: none">

            <div class="col-md-6">

              {{ csrf_field() }}
              <input type="hidden" id="change-id" name="id_equipment">
              <input type="hidden" id="change-value" name="value">
            
              <button class="btn btn-success option" id="available" value="0">Disponible</button>

            </div>

            <div class="col-md-6">
              
              <button class="btn btn-primary option" id="maintenance" value="2">Mantenimiento</button>
              
            </div>

            <div class="col-md-6">

              <button class="btn btn-warning option" id="out-service" value="3">Fuera de servicio</button>

            </div>

            <div class="col-md-6">

              <button type="button" class="btn btn-danger option" id="back-panel-one">Volver</button>

            </div>

          </div>

        </form>
            
      </div>

    </div>

  </div>

</div>

<div class="modal fade" id="modal-info-alumn" data-backdrop='static' data-keyboard=false>

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="loader-modal">
        <div class="loader-spinner">Loading...</div>
      </div>

      <div class="modal-header text-center">

        <h4 class="center">Información del alumno</h4>

      </div>

      <div class="modal-body">
          <table class="table">
            <thead>
              <tr>
                <th>Matricula</th>
                <th>Nombre</th>
                <th>Primer apellido</th>
                <th>Segundo apellido</th>
              </tr>
            </thead>
            <tbody>
              <tr id="row-info">
                
              </tr>
            </tbody>
          </table>
      </div>

      <div class="modal-footer">
        <button class="btn btn-default w-100" type="button" data-dismiss="modal">Cerrar</button>
      </div>

    </div>

  </div>

</div>

<script>

  $("#form-change-status").submit(function(e) {
    var $form = $("#form-change-status");
    e.preventDefault();
    swal.fire({
      title: '¿estas seguro de cambiar el estado de la computadora?',
      text: "¡Si no lo estás puedes cancelar!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Si, estoy seguro'
    }).then((result)=>
    {
      if (result.value)
      {
        $form.get(0).submit();
      }
    });
  });

  $("#out-service, #maintenance, #available").on("click", function() {
    var id = $(this).attr("value");
    $("#change-value").val(id);
  });

  $(".changetab").click(function() {
    var id = $(this).attr("id");
    var href = $(this).attr("href");
    localStorage.setItem("tab", id);
    localStorage.setItem("panel", href);
  });

  $(document).ready(function() {
    let id = localStorage.getItem("tab");
    let panel = localStorage.getItem("panel");
    if (id) {
      $(".changetab").removeClass("active");
      $(".tab-pane").removeClass("active");
      //$(".tab-pane").removeClass("show");
      $("#"+id).addClass("active",true);
      $(panel).addClass("active",true);
      //$(panel).addClass("show",true);
    }
  });

  $(".takeit").click(function() {
    $(".loader-modal").show();
    var id = $(this).attr("equipmentId");

    $.get("{{ route('departament.logs.equipment.getEquipment') }}?id="+id,function(data) {

      $("#title-modal").html(data.code);

      if (data.alumn) {
        $("#alumn-info").removeAttr("disabled");
      } else {
        $("#alumn-info").attr("disabled", "disabled");
      }

      $("#id_equipment").val(data.id);
      $("#change-id").val(data.id);
      $(".loader-modal").hide();
    });

    $("#modal-quipment-info").modal("show");
  });

  $("#delete-equipment").click(function() {
    var id = $("#id_equipment").val();
    swal.fire({
        title: '¿Seguro que deseas borrar este equipo?',
        text: "¡Si lo haces todos los datos relacionados serán borrados!",
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
          window.location = "/departaments/logs/equipments/delete/"+id;
        }
    });
  });

  $("#alumn-info").click(function() {
    $(".loader-modal").show();
    var id = $("#id_equipment").val();
    $.get("{{ route('departament.logs.equipment.alumnData') }}?id="+id,function(data) {

      $("#row-info").empty();
      $("#row-info").append('<td>'+data.Matricula+'</td><td>'+data.Nombre+'</td><td>'+data.ApellidoPrimero+'</td><td>'+data.ApellidoSegundo+'</td>');
      $(".loader-modal").hide();
    });

    $("#modal-info-alumn").modal('show');
  });

  $("#change-status").click(function() {
    $("#panel-one-modal").fadeOut(500, function() {
      $("#panel-two-modal").fadeIn(500);
    });
  });

  $("#back-panel-one").click(function() {
    $("#panel-two-modal").fadeOut(500, function() {
      $("#panel-one-modal").fadeIn(500);
    });
  });
</script>

@stop
