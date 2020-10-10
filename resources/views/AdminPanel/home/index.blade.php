@extends('AdminPanel.main')

@section('content-admin')

<style>
  .special-orange-1 {
    background: rgb(230,124,8) !important;
  }

  .special-orange-2 {
    background: rgb(251,142,33) !important;
  }

  .special-orange-3 {
    background: rgb(252,171,90) !important;
  }

  .special-orange-4 {
    background: rgb(254,203,80) !important
  }
</style>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Bienvenido <small>{{Auth::guard("admin")->user()->name}}</small></h1>
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

            <div class="small-box {{$config->period_id == null ? 'bg-default' : 'bg-info special-orange-1'}}">

              <div class="inner">

                <h3>Periodo</h3>

                <p>{{$config->period_id != null ? $config->period->clave : 'sin asignar'}}</p>

              </div>

              <div class="icon">

                <i class="fa fa-archive"></i>

              </div>

              <a href="" class="small-box-footer" data-toggle='modal' data-target='#modalPeriod'>Ver <i class="fas fa-arrow-circle-right"></i></a>

            </div>
            
          </div>

          <div class="col-md-3 col-sm-12">

            <div class="small-box {{$config->open_inscription == 0 ? 'bg-default' : 'bg-info special-orange-2'}}">

              <div class="inner">

                <h3>Inscripciones</h3>

                <p>{{$config->open_inscription == 0 ? 'Cerradas' : 'Abiertas'}}</p>

              </div>

              <div class="icon">

                <i class="fa fa-clone"></i>

              </div>

              <a href="{{ route('admin.settings') }}" class="small-box-footer">Cambiar <i class="fas fa-arrow-circle-right"></i></a>

            </div>
            
          </div>

          <div class="col-md-3 col-sm-12">

            <div class="small-box {{$config->laep_id != 0 ? 'bg-info special-orange-3' : 'bg-default'}}">

              <div class="inner">

                <h3>Administración</h3>

                <p>{{$config->getAdministracionData() ? $config->getAdministracionData()['Nombre'] : 'sin asignar'}}</p>

              </div>

              <div class="icon">

                <i class="fa fa-bars"></i>

              </div>

              <a href="{{ route('admin.settings') }}" class="small-box-footer">Cambiar <i class="fas fa-arrow-circle-right"></i></a>

            </div>
            
          </div>

          <div class="col-md-3 col-sm-12">

            <div class="small-box {{$config->lata_id != 0 ? 'bg-info special-orange-4' : 'bg-default'}}">

              <div class="inner">

                <h3>Turismo</h3>

                <p>{{$config->getTuristmoData() ? $config->getTuristmoData()['Nombre'] : 'sin asignar'}}</p>

              </div>

              <div class="icon">

                <i class="fa fa-bars"></i>

              </div>

              <a href="{{ route('admin.settings') }}" class="small-box-footer">Cambiar <i class="fas fa-arrow-circle-right"></i></a>

            </div>
            
          </div>

          <div class="col-md-3 col-sm-12">

            <div class="small-box {{$config->price_inscription != null ? 'bg-success' : 'bg-default'}}">

              <div class="inner">

                <h3>Costo</h3>

                <p>${{$config->price_inscription ? number_format($config->price_inscription,2) : 'sin asignar'}}</p>

              </div>

              <div class="icon">

                <i class="fas fa-money-bill-wave"></i>

              </div>

              <a href="{{ route('admin.settings') }}" class="small-box-footer">Cambiar <i class="fas fa-arrow-circle-right"></i></a>

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
                <tbody>
                  <tr>
                    <td>{{$config->period->id}}</td>
                    <td>{{$config->period->clave}}</td>
                    <td>{{$config->period->año}}</td>
                    <td>{{$config->period->ciclo}}</td>
                    <td>{{$config->period->semestre}}</td>
                  </tr>
                </tbody>
                
              </table>

            </div>

          </div>

          <div class="col-md-12">
            <a class="btn btn-warning" href="{{route('admin.settings')}}" style="color:white;">Cambiar periodo</a>
          </div>

        </div>
          
      </div>

      <div class="modal-footer justify-content">

        <div class="col-sm container-fluid">

          <div class="row">

            <div class="col-md-6 btn-group">
                <button type="button" class="btn btn-danger .px-2" id="close-period" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
            </div>

          </div>

        </div>

      </div>

    </div>

  </div>

</div>

<script src="{{asset('js/admin/home.js')}}"></script>

@stop
