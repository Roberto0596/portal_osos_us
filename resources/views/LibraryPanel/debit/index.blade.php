@extends('LibraryPanel.main')

@section('content-library')

<div class="content-wrapper">

  <section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-9">

          <div class="row">
            <div class="col-md-4"><h1>Procesar <small>pagos</small></h1></div>
          </div>

        </div>

        <div class="col-sm-3">

          <ol class="breadcrumb float-sm-right">

            <button data-toggle="modal" data-target="#modalDebit" class="btn btn-warning button-custom"><i class="fa fa-fw fa-plus"></i>Nuevo adeudo</button>

          </ol>

        </div>

      </div>

    </div>

  </section>

  <section class="content">

    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <h4>Filtros</h4>
          </div>
          <div class="col-md-4">
            <label for="">Estatus</label>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                  <span class="input-group-text">
                  <i class="fas fa-toggle-on"></i></span>
              </div>

              <select id="mode" class="form-control form-control-lg">
                @php
                $array = [["value" => "0","text"=>"Pendientes", "selected"=>false],
                              ["value" => "1","text"=>"Pagados", "selected"=>false]];
                @endphp

                @if(session()->has('mode'))
                  @php
                    $mode = session()->get('mode');
                    switch($mode["mode"])
                    {
                      case 0:
                        $array[0]["selected"]=true;
                        break;
                      case 1:
                        $array[1]["selected"]=true;
                        break;
                    }
                  @endphp
                @else
                  $array[0]["selected"]=true;
                @endif
                @foreach($array as $key => $value)
                  <option value="{{$value['value']}}" @if($value['selected']==true) selected @endif>{{$value['text']}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <label for="">Periodo</label>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                  <span class="input-group-text">
                  <i class="fas fa-th-list"></i></span>
              </div>

              <select id="period" class="form-control form-control-lg">
                @foreach(selectTable('period') as $key => $value)
                  @if(session()->has('mode'))
                    @php
                      $mode = session()->get('mode');
                      $selected = '';
                      if($mode["period"] == $value->id) {
                        $selected = 'selected';
                      }
                    @endphp
                   <option value="{{$value->id}}" {{$selected}}>{{$value->clave}}</option>
                  @else
                   <option value="{{$value->id}}">{{$value->clave}}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-4">
            <label for="">Concepto</label>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                  <span class="input-group-text">
                  <i class="fas fa-asterisk"></i></span>
              </div>

              <select id="concept" class="form-control form-control-lg">
                <option value="all">Todos</option>
                @foreach(getUnAdminDebitType() as $key => $value)
                  @if(session()->has('mode'))
                    @php
                      $mode = session()->get('mode');
                      $selected = '';
                      if($mode["concept"] == $value->id) {
                        $selected = 'selected';
                      }
                    @endphp
                   <option value="{{$value->id}}" {{$selected}}>{{$value->concept}}</option>
                  @else
                   <option value="{{$value->id}}">{{$value->concept}}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="card">

      <div class="card-body">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">

        <table class="table table-bordered table-hover dt-responsive tableDebits">

          <thead>

            <tr>
              <th style="width: 10px">#</th>
              <th>Acciones</th>
              <th>Alumno</th>
              <th>Email</th>
              <th>Descripción</th>
              <th>Importe</th>
              <th>Matricula</th>
              <th>Estado</th>
              <th>Fecha</th>
              <th>Carrera</th>
              <th>Localidad</th>
             
            </tr>

          </thead>

        </table>

      </div>

    </div>

  </section>

</div>
 <!-- Modal crear nuevo adeudo -->

<div class="modal fade" id="modalDebit">

  <div class="modal-dialog modal-lg">

    <div class="modal-content">

        <form method="post" action="{{route('library.debit.save')}}">
            
            {{ csrf_field() }}

            <div class="modal-header">

                <h4 class="modal-title">GENERAR NUEVO ADEUDO</h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
        
            <div class="modal-body">

                <div class="row">

                    <div class="col-md-6">           

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-credit-card"></i></span>
                            </div>

                            <select name="debit_type_id" id="debit_type_id" class="form-control form-control-lg select2" style="width: 88%">
                              <option value="" disabled selected>Seleccione un concepto</option>
                              @foreach(getUnAdminDebitType()  as $key => $value)
                              <option value="{{$value->id}}">{{$value->concept}}</option>
                              @endforeach
                            </select>

                        </div>

                    </div>

                    <div class="col-md-6">           

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-dollar-sign"></i></span>
                            </div>

                            <input type="number" step="any" min="0" name="amount" placeholder="¿Cual es el monto?" class="form-control form-control-lg" required>

                        </div>

                    </div>

                    <div class="col-md-12">           

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-user"></i></span>
                            </div>

                            <select class="form-control form-control-lg select2" style="width: 88%" name="id_alumno" require>
                                <option value="">Seleccione un alumno</option>
                                @php
                                    $alumnos = selectUsersWithSicoes();
                                @endphp

                                @foreach($alumnos as $key => $value)
                                <option value="{{$value->id_alumno}}">{{$value->email." ".$value->name." ".$value->lastname}}</option>
                                @endforeach

                            </select>

                        </div>

                    </div>

                    <div class="col-md-12">           

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-ad"></i></span>
                            </div>

                            <textarea type="text" name="description" placeholder="Ingrese una descripción" class="form-control form-control-lg" required></textarea>

                        </div>

                    </div>

                </div>

            </div>

            <div class="modal-footer justify-content">

                <div class="col-sm container-fluid">

                    <div class="row">

                        <div class=" col-sm-6 btn-group">

                        <button id="cancel" type="button" class="btn btn-danger .px-2 " data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>

                        </div>

                        <div class=" col-sm-6 btn-group">

                        <button type="submit" id="sale" class="btn btn-success .px-2"><i class="fa fa-check"></i> Guardar</button>
                        
                        </div>

                    </div>

                </div>

            </div>

       </form>

    </div>

  </div>

</div>


 <!-- Modal Editar-->
<div class="modal fade" id="modalEdit">

  <div class="modal-dialog">

    <div class="modal-content">

      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="tokenUpdate">

      <div class="modal-header">

          <h4 class="modal-title">EDITAR ADEUDO</h4>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>

      </div>

      <form method="post" action="{{route('library.debit.update')}}">
            
        {{ csrf_field() }}

        <input type="hidden" id="debitId" name="debitId">           
        
        <div class="modal-body">

          <div class="row">

            <div class="col-md-12">           

                <label for="">Monto</label>

                <div class="input-group mb-3">

                    <div class="input-group-prepend">
                        <span class="input-group-text">
                        <i class="fas fa-dollar-sign"></i></span>
                    </div>

                    <input type="number" step="any" min="0" name="amount" id="amount" placeholder="Monto" class="form-control" required>

                </div>

            </div>

            <div class="col-md-12">  

                <label for="">Descripción</label>

                <div class="input-group mb-3">

                    <div class="input-group-prepend">
                        <span class="input-group-text">
                        <i class="fas fa-ad"></i></span>
                    </div>

                    <textarea type="text" name="description" id="description" placeholder="Ingrese una descripción" class="form-control" required></textarea>

                </div>

            </div>

          </div>

        </div>

        <div class="modal-footer">

          <div class="row" style="width: 100%;">

              <div class=" col-md-6">

                <button id="closeDetails" type="button" class="btn btn-danger .px-2" 
              data-dismiss="modal" style="width: 100%"><i class="fa fa-times"></i> Cerrar</button>

              </div>

              <div class=" col-md-6">

                <button class="btn btn-success .px-2" style="width: 100%"><i class="fa fa-check" ></i> Guardar</button>

              </div>

          </div>

        </div>

      </form>

    </div>

  </div>

</div>

<script src="{{ asset('js/library/debit.js')}}"></script>

@stop