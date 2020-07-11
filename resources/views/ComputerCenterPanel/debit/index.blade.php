@extends('ComputerCenterPanel.main')

@section('content-computer')

<style>
  .page-item.active .page-link {
    background-color: #fd7e14;
    border-color: #fd7e14;
  }
  .textAndButton{
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    align-items: center;
  }

  
 
  .custom{
    background-color: #fd7e14;
    border-color: #fd7e14;
    color: white;
  }

  .custom:hover{
    background-color: #e96c06;
    border-color: #e96c06;
    color: white;
  }
  .modal-header{
    background-color: #28a745;
    color: white;
  }
 
 
</style>

<div class="content-wrapper">

  <section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1>Procesar pagos</h1>

        </div>

        <div class="col-sm-6">

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

        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">

        <table class="table table-bordered table-hover tableDebits">

          <thead>

            <tr>
              <th style="width: 10px">#</th>
              <th>Concepto</th>
              <th>Monto</th>
              <th>Encargado</th>
              <th>Alumno</th>
              <th>Estado</th>
              <th>Fecha</th>
              <th>Acciones</th>
            </tr>

          </thead>

        </table>

      </div>

    </div>

  </section>

</div>

<div class="modal fade" id="modalDebit">

  <div class="modal-dialog modal-lg">

    <div class="modal-content">

        <form method="post" action="{{route('computo.debit.save')}}">
            
            {{ csrf_field() }}

            <div class="modal-header">

                <h4 class="modal-title">Generar un adeudo</h4>

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

                            <input type="text" name="concept" id="concept" placeholder="¿Cual es el concepto?" class="form-control form-control-lg" required>

                        </div>

                    </div>

                    <div class="col-md-6">           

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-credit-card"></i></span>
                            </div>

                            <input type="number" step="any" min="0" name="amount" id="amount" placeholder="¿Cual es el monto?" class="form-control form-control-lg" required>

                        </div>

                    </div>

                    <div class="col-md-6">           

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-credit-card"></i></span>
                            </div>

                            <select class="form-control form-control-lg" id="id_alumno" name="id_alumno" style="width:88%" require>
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

                </div>

            </div>

            <div class="modal-footer justify-content">

                <div class="col-sm container-fluid">

                    <div class="row">

                        <div class=" col-sm-6 btn-group">

                        <button id="cancel" type="button" class="btn btn-danger .px-2 " data-dismiss="modal"><i class="fa fa-times"></i> Eliminar</button>

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

<!-- Modal -->
<div class="modal fade" id="modalPay">

    <div class="modal-dialog">

        <div class="modal-content">

        <div class="modal-header">

            <h5 class="modal-title" id="exampleModalLabel">CAMBIAR EL ESTADO DEL PAGO</h5>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
        </div>

        <div class="modal-body">

            <table class="table">
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th>Alumno</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody id="body-table">
                </tbody>
            </table>
            <input type="hidden" name="_token" value="{{ csrf_token() }}" id="tokenModal">
            <br>
            <form action="{{route('computo.debit.update')}}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" id="DebitId" name="DebitId">
                <div class="modal-footer">

                    <div class="col-sm container-fluid">

                        <div class="row">

                            <div class=" col-sm-6 btn-group">

                            <button type="button" class="btn btn-danger .px-2 " data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>

                            </div>

                            <div class=" col-sm-6 btn-group">

                            <button type="submit" class="btn btn-success .px-2"><i class="fa fa-check"></i> Guardar</button>
                            
                            </div>

                        </div>

                    </div>
                </div>
            </form>
        </div>
        </div>
    </div>
    </div>
    <!-- TerminaModal -->

<script src="{{ asset('js/computercenter/debit.js')}}"></script>

@stop