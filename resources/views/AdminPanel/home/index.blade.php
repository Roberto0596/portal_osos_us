@extends('AdminPanel.main')

@section('content-admin')

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Bienvenido {{Auth::guard("admin")->user()->name}}</h1>
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

            <div class="small-box bg-success">

              <div class="inner">

                <h3>Periodo</h3>

                <p>{{$period->clave}}</p>

              </div>

              <div class="icon">

                <i class="fa fa-user"></i>

              </div>

              <a href="" class="small-box-footer" data-toggle='modal' data-target='#modalPeriod'>Cambiar<i class="fas fa-arrow-circle-right"></i></a>

            </div>
            
          </div>

        </div>

      </div>

    </div>

  </section>

</div>

<div class="modal fade" id="modalPeriod" data-backdrop='static' data-keyboard=false>

  <div class="modal-dialog modal-lg">

    <div class="modal-content">

      <div class="modal-header">

        <h3>Periodo en curso</h3>

      </div>

      <form action="{{route('admin.period.save',$period)}}" method="post">

        <div class="modal-body">
            
          {{ csrf_field() }}

          <div class="row">

            <div class="col-md-12">

              <div class="form-group">

              <div class="panel">periodo guardado</div>

                <table class="table">

                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Clave</th>
                      <th>Año</th>
                      <th>Ciclo</th>
                      <th>Semestre</th>
                    </tr>
                  </thead>
                  @php
                    $period = selectTable("period");
                  @endphp
                  <tbody>
                    <tr>
                      @foreach($period as $value)
                      <td>{{$value->id}}</td>
                      <td>{{$value->clave}}</td>
                      <td>{{$value->año}}</td>
                      <td>{{$value->ciclo}}</td>
                      <td>{{$value->semestre}}</td>
                      @endforeach
                    </tr>
                  </tbody>
                  
                </table>

              </div>

            </div>

            <div class="col-md-12">
              <button class="btn btn-warning" id="change-period" style="color:white;">Cambiar periodo</button>
            </div>

            <div class="col-md-12" style="display: none" id="content-period">

              <div class="form-group">

              <div class="panel">Cambiar periodo</div>

                <select name="period" class="form-control select2" id="period" style="width: 100%" required>
                  @php
                    $periods = selectSicoes("Periodo");
                  @endphp
                  <option value="">Periodos disponibles</option>
                  @foreach($periods as $value)
                    <option value="{{$value['PeriodoId']}}">{{$value['Clave']}}</option>
                  @endforeach
                </select>

              </div>

            </div>

          </div>
            
        </div>

        <div class="modal-footer justify-content">

          <div class="col-sm container-fluid">

            <div class="row">

              <div class="col-md-6 btn-group">
                  <button type="button" class="btn btn-danger .px-2" id="close-period" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
              </div>

              <div class="col-md-6 btn-group">
                <button class="btn btn-success" type="submit" id="button" style="display: none"><i class="fa fa-check"></i> Enviar</button>
              </div>

            </div>

        </div>

      </form>

    </div>

    </div>

  </div>

</div>

<script src="{{asset('js/admin/home.js')}}"></script>

@stop
