@extends('layout')

@section('content')

<link rel="stylesheet" href="{{ asset('css/computer_log.css') }}">

<div class="content-custom">

    <div class="back2" style="padding-top: 0 !important">

        <div class="classroom-container">

            <div class="classroom-header">

                <img src="{{ asset('img/temple/unisierra.png') }}" alt="">

                <div class="row">

                    <div class="col-md-12">

                        <div class="float-right">

                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                @foreach($classrooms as $key => $item)
                                    <li class="nav-item">                                
                                          <a class="nav-link @if($key == 0) active @endif" id="{{$item->code}}" data-toggle="tab" href="#{{str_replace(' ', '_', $item->name)}}" role="tab" aria-controls="{{$item->num}}-tab">{{ $item->name }}</a>
                                    </li>
                                @endforeach
                            </ul>

                        </div>

                    </div>

                </div>

            </div>

            <div class="classroom-body">

                <div class="scroll">

                    <div class="tab-content" id="myTabContent">
                        @foreach($classrooms as $key => $item)
                        <div class="tab-pane fade show @if($key == 0) active @endif" id="{{str_replace(' ', '_', $item->name)}}" role="tabpanel" aria-labelledby="{{$item->num}}-tab">

                            <div class="row">

                                @foreach($item->getEquipments() as $value)
                                  <div class="col-md-2">

                                      <div class="item-equipment">
                                          <button class="btn btn-primary takeit"
                                          equipmentId="{{$value->id}}"
                                          num="{{$value->num}}"
                                          code="{{$value->code}}"
                                          @if($value->status != 0) disabled @endif

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

            <a href="{{ route('logs.login') }}" class="btn btn-success button-absolute"><i class="fa fa-th"></i></a>

         </div> 

    </div>

</div>

<div class="modal fade" id="modal" data-backdrop='static' data-keyboard=false>

  <div class="modal-dialog modal-sm">

    <div class="modal-content">

      <div class="modal-header text-center">

        <h3 class="center">Confirma reserva</h3>

      </div>
      <form action="{{ route('logs.classroom.save') }}" method="post">

        {{ csrf_field() }}

        <div class="modal-body">

          <div class="row">

            <div class="col-md-12">
              <label for="">Equipo</label>
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <div class="input-group-text">
                    <i class="fa fa-th"></i>
                  </div>
                </div>
                <input type="text" class="form-control" id="num_equipment" name="num_equipment" readonly>
                <input type="hidden" name="id_equipment" id="id_equipment">
              </div>
            </div>

            <div class="col-md-12">
              <label for="">Hora</label>
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <div class="input-group-text">
                    <i class="fa fa-th"></i>
                  </div>
                </div>
                <input type="text" class="form-control" id="time" name="time" readonly>
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

<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script>

  $(".takeit").click(function() {
    var id_equipment = $(this).attr("equipmentId");
    var code = $(this).attr("code");
    var num = $(this).attr("num");
    $("#modal").modal("show");
    $("#id_equipment").val(id_equipment);
    $("#time").val(moment().format('HH:mm:ss'));
    $("#num_equipment").val(code + " " + num);
  });

</script>

@stop
