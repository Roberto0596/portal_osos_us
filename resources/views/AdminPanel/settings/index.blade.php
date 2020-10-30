@extends('AdminPanel.main')

@section('content-admin')

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Configuración <small>General</small></h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active"><a href="#">Configuracion</a></li>
          </ol>
        </div>
        <div class="col-sm-12">
          <h5 class="text-right">Ultima actualización <small>{{ $instance->updated_at != null ? $instance->updated_at : 'Sin registro' }}</small></h5>
        </div>
      </div>
    </div>
  </section>

  <section class="content">

    <form action="{{ route('admin.save.setting', $instance) }}" method="post">

      {{ csrf_field() }}
      
      <div class="row">
        
        <div class="col-md-3">

            <div class="card card-outline card-warning collapsed-card">

              <div class="card-header">

                <h3 class="card-title">Periodo {{ $instance->period_id != null ? $instance->period->clave : 'Sin asignar'}}</h3>

                <div class="card-tools">

                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                  </button>

                </div>

              </div>

              <div class="card-body" style="display: none;">

                <div class="form-group">

                  <div class="panel">Cambiar periodo</div>

                    <select name="period_id" class="form-control select2" id="period_id" style="width: 100%">
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

        <div class="col-md-3">

          <div class="card card-outline card-warning collapsed-card">

            <div class="card-header">

              <h3 class="card-title">Inscripciones {{ $instance->open_inscription != null ? ($instance->open_inscription == 0 ? 'cerradas' : 'abiertas') : 'cerradas'}}</h3>

              <div class="card-tools">

                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                </button>

              </div>

            </div>

            <div class="card-body" style="display: none;">

              <div class="form-group">

                <div class="panel">Cambiar estado</div>

                  <select name="open_inscription" class="form-control select2" id="open_inscription" style="width: 100%">
                      <option value="0" {{ $instance->open_inscription != null ? ($instance->open_inscription == 0 ? 'selected' : '') : 'selected'}}>Inscripciones cerradas</option>
                      <option value="1" {{ $instance->open_inscription != null ? ($instance->open_inscription == 1 ? 'selected' : '') : ''}}>Inscripciones abiertas</option>
                  </select>

                </div>

            </div>

          </div>

        </div>

        <div class="col-md-3">

          <div class="card card-outline card-warning collapsed-card">

            <div class="card-header">

              <h3 class="card-title">Turismo {{ $instance->lata_id != null ? strtolower($instance->getTuristmoData()["Clave"]) : 'Sin registro' }}</h3>

              <div class="card-tools">

                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                </button>

              </div>

            </div>

            <div class="card-body" style="display: none;">

              <div class="form-group">

                <div class="panel">Cambiar plan de estudio</div>

                  <select name="lata_id" class="form-control select2" id="lata_id" style="width: 100%">
                    <option value="">Seleccione un plan</option>
                    @php
                      $planesEstudio = selectSicoes("PlanEstudio");
                    @endphp

                    @foreach($planesEstudio as $key => $value)
                    <option value="{{$value['PlanEstudioId']}}">{{ $value['Nombre'] }}</option>
                    @endforeach
                  </select>

                </div>

            </div>

          </div>

        </div>

        <div class="col-md-3">

          <div class="card card-outline card-warning collapsed-card">

            <div class="card-header">

              <h3 class="card-title">Administración {{ $instance->lata_id != null ? strtolower($instance->getAdministracionData()["Clave"]) : 'Sin registro' }}</h3>

              <div class="card-tools">

                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                </button>

              </div>

            </div>

            <div class="card-body" style="display: none;">

              <div class="form-group">

                <div class="panel">Cambiar plan de estudio</div>

                  <select name="laep_id" class="form-control select2" id="laep_id" style="width: 100%">
                    <option value="">Seleccione un plan</option>
                    @php
                      $planesEstudio = selectSicoes("PlanEstudio");
                    @endphp

                    @foreach($planesEstudio as $key => $value)
                    <option value="{{$value['PlanEstudioId']}}">{{ $value['Nombre'] }}</option>
                    @endforeach
                  </select>

                </div>

            </div>

          </div>

        </div>

        <div class="col-md-3">

          <div class="card card-outline card-warning collapsed-card">

            <div class="card-header">

              <h3 class="card-title">Colegiatura ${{ $instance->price_inscription != null ? number_format($instance->price_inscription,2) : 'sin asignar'}}</h3>

              <div class="card-tools">

                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                </button>

              </div>

            </div>

            <div class="card-body" style="display: none;">

              <div class="form-group">

                <div class="panel">Cambiar el precio de la colegiatura</div>

                  <input type="text" name="price_inscription" id="price_inscription" value="{{ $instance->price_inscription }}" class="form-control">

                </div>

            </div>

          </div>

        </div>

        <div class="col-sm-12">
            <div class="btn-group">
              <button class="btn btn-success" type="submit"><i class="fas fa-check"></i> Guardar</button>
            </div>        
        </div>

      </div>

    </form>

  </section>

</div>

<script src="{{asset('js/admin/settings.js')}}"></script>

@stop
