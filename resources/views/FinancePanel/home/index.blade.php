@extends('FinancePanel.main')

@section('content-finance')

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Bienvenido <small>{{Auth::guard("finance")->user()->name}}</small></h1>
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
      
      <div class="card-body">

        <div class="row">
          <div class="col-md-12">
            <h4>Tablero</h4>
          </div>

          <div class="col-md-3 col-sm-12">

            <div class="small-box {{$debits == 0 ? 'bg-default' : 'bg-info'}}">

              <div class="inner">

                <h3>Adeudos</h3>

                <p>{{$debits == 0 ? 'sin adeudos' : 'hay '.$debits.' nuevos adeudos'}}</p>

              </div>

              <div class="icon">

                <i class="fas fa-credit-card"></i>

              </div>

              <a href="{{ route('finance.debit') }}" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>

            </div>
            
          </div>

          <div class="col-md-4 col-sm-12">

            <div class="small-box bg-success" data-toggle="modal"data-target="#modalDebit">

              <div class="inner">

                <h3>Generar Adeudo</h3>

                <p>Genera un nuevo adeudo a un estudiante</p>

              </div>

              <div class="icon">

                <i class="fas fa-plus"></i>

              </div>

              <div class="small-box-footer">
                &nbsp;
              </div>

            </div>
            
          </div>


          <div class="col-md-4 col-sm-12">

            <div class="small-box bg-primary" data-toggle="modal"data-target="#modalSerieTicket">

              <div class="inner">

                <h3>Cambiar Serie</h3>

                <p>Cambia la serie de los tickets</p>

              </div>

              <div class="icon">

                <i class="fas fa-plus"></i>

              </div>

              <div class="small-box-footer">
                &nbsp;
              </div>

            </div>
            
          </div>

        </div>

      </div>

    </div>

  </section>

</div>



<div class="modal fade" id="modalDebit">

  <div class="modal-dialog modal-lg">

    <div class="modal-content">

        <form method="post" action="{{route('finance.debit.save')}}">
            
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

                            <select name="debit_type_id" id="debit_type_id" class="form-control">
                              <option value="" disabled selected>Seleccione un concepto</option>
                              @foreach(getDebitType() as $key => $value)
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

                            <input type="number" step="any" min="0" name="amount" placeholder="¿Cual es el monto?" class="form-control" required>

                        </div>

                    </div>

                    <div class="col-md-6">           

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-user"></i></span>
                            </div>

                            <select class="form-control" id="id_alumno" name="id_alumno" style="width:88%" require>
                                <option value="">Seleccione un alumno</option>
                                @php
                                    $alumnos = selectSicoes("Alumno");
                                @endphp

                                @foreach($alumnos as $key => $value)
                                <option value="{{$value['AlumnoId']}}">{{$value["Matricula"]." ".$value["Nombre"]}}</option>
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

                            <textarea type="text" name="description" placeholder="Ingrese una descripción" class="form-control" required></textarea>

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







<div class="modal fade" id="modalSerieTicket">

  <div class="modal-dialog modal-lg">

    <div class="modal-content">

        <form method="post" action="{{ route('finance.settings.changeSerie')}}">
            
            {{ csrf_field() }}

            <div class="modal-header">

                <h4 class="modal-title">Cambiar Serie de Tickets</h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
        
            <div class="modal-body">

              <div>
                <p>
                  Al momento de cambiar la serie, el conteo de los tickets se reiniciará a 00001
                </p>
              </div>

                <div class="row">

                    <div class="col-md-12">           

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-ad"></i></span>
                            </div>

                            <input type="text" id="serie" name="serie" placeholder="Ingrese serie" class="form-control" required>

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

<script>
  $("#id_alumno").select2({
    width: 'resolve'
  });

  $(document).ready(function(){

    <?php
      $config = getConfig();

                    
    ?>

    $('#serie').val('{{$config->ticket_serie}}');
    
  
  });

</script>

@stop
