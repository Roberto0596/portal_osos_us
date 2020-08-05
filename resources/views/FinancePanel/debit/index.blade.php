@extends('FinancePanel.main')

@section('content-finance')

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

                            <select name="debit_type_id" id="debit_type_id" class="form-control form-control-lg">
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

                            <input type="number" step="any" min="0" name="amount" id="amount" placeholder="¿Cual es el monto?" class="form-control form-control-lg" required>

                        </div>

                    </div>

                    <div class="col-md-6">           

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-user"></i></span>
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

                    <div class="col-md-12">           

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-ad"></i></span>
                            </div>

                            <textarea type="text" name="description" id="description" placeholder="Ingrese una descripción" class="form-control form-control-lg" required></textarea>

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

  <div class="modal-dialog modal-lg">

    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="tokenModal">

        <form method="post" action="{{route('finance.debit.update')}}">
            
            {{ csrf_field() }}

            <input type="hidden" id="DebitIdUpdate" name="DebitId">           

            <div class="modal-header">

                <h4 class="modal-title">EDTAR ADEUDO</h4>

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
                                <i class="fas fa-dollar-sign"></i></span>
                            </div>

                            <input type="number" step="any" min="0" name="amount" id="EditAmount" placeholder="¿Cual es el monto?" class="form-control form-control-lg" required>

                        </div>

                    </div>

                    <div class="col-md-6">           

                      <div class="input-group mb-3">

                          <div class="input-group-prepend">
                              <span class="input-group-text">
                              <i class="fas fa-credit-card"></i></span>
                          </div>

                          <select class="form-control form-control-lg" id="EditStatus" name="status" style="width:88%" require>
                              <option value="">Cambiar estado de pago</option>
                              <option value="0">Pendiente</option>
                              <option value="1">Pagado</option>
                          </select>

                      </div>

                    </div>

                    <div class="col-md-12">           

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-user"></i></span>
                            </div>

                            <select class="form-control form-control-lg" id="EditId_alumno" name="id_alumno" style="width:88%" require>
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

                            <textarea type="text" name="EditDescription" id="EditDescription" placeholder="Ingrese una descripción" class="form-control form-control-lg" required></textarea>

                        </div>

                    </div>

                </div>

            </div>

            <div class="modal-footer justify-content">

                <div class="col-sm container-fluid">

                    <div class="row">

                        <div class=" col-sm-6 btn-group">

                        <button id="cancelEdit" type="button" class="btn btn-danger .px-2 " data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>

                        </div>

                        <div class=" col-sm-6 btn-group">

                        <button type="submit" id="saleEdit" class="btn btn-success .px-2"><i class="fa fa-check"></i> Guardar</button>
                        
                        </div>

                    </div>

                </div>

            </div>

       </form>

    </div>

  </div>

</div>

<!-- TerminaModal -->

 <!-- Modal  de detalles de pago -->
 <div class="modal fade" id="modalShowDetails" data-backdrop='static' data-keyboard=false>

    <div class="modal-dialog modal-lg">
  
      <div class="modal-content">
          <input type="hidden" name="_token" value="{{ csrf_token() }}" id="tokenModal">
  
         
              
              
              {{ csrf_field() }}
              <input type="hidden" id="DebitIdUpdate" name="DebitId">
             
  
              <div class="modal-header">
  
                  <h4 class="modal-title">DETALLES DEL PAGO</h4>
  
                  
  
              </div>
          
              <div class="modal-body custom-modal">

                <div id="loader" class="loader"></div>
                <h6 id="detail-id"></h6>
                <h6 id="detail-paymentMethod"></h6>
                <h6 id="detail-reference"></h6>
                <h6 id="detail-amount"></h6>
               
  
              </div>
  
              <div class="modal-footer justify-content">
  
                  <div class="col-sm container-fluid">
  
                      <div class="row" style="margin-left: 32%">
  
                          <div class=" col-sm-6 btn-group">
  
                          <button id="closeDetails" type="button" class="btn btn-danger .px-2 " 
                          data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
  
                          </div>
  
                      </div>
  
                  </div>
  
              </div>
  
         
  
      </div>
  
    </div>
  
  </div>

 

  <script src="{{asset('js/financePanel/debit.js')}}"></script>
 
@stop