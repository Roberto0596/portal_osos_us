@extends('AdminPanel.main')

@section('content-admin')

<div class="content-wrapper">

  <section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1>Alumnos registrados</h1>

        </div>

        <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right">

            <li class="breadcrumb-item"><a href="#">Home</a></li>

            <li class="breadcrumb-item active"><a href="#">Problemas</a></li>

          </ol>

        </div>

      </div>

    </div>

  </section>

  <section class="content">

    <div class="card">

      <div class="card-body">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">

        <table class="table table-bordered table-hover tableAlumns">

          <thead>

            <tr>
              <th style="width: 10px">#</th>
              <th>Matricula</th>
              <th>Nombre(s)</th>
              <th>Apellido(s)</th>
              <th>Email</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>

          </thead>

        </table>

      </div>

    </div>

  </section>

</div>

<div class="modal fade" id="modal-edit-alumn" data-backdrop='static' data-keyboard=false>

  <div class="modal-dialog modal-lg">

    <div class="modal-content">

      <div class="modal-header">

        <h3>Editar datos de sicoes</h3>

      </div>

      <form action="{{route('admin.alumns.update')}}" method="post">

        <div class="modal-body">
            
          {{ csrf_field() }}

          <input type="hidden" value="{{ csrf_token() }}" id="token2">
          <input type="hidden" name="id_alumn" id="id_alumn">

          @php
            $planesEstudio = selectSicoes("PlanEstudio");
            $period = selectCurrentPeriod();
            $EncGrupos = selectSicoes("EncGrupo","PeriodoId",$period->id);
          @endphp

          <div class="row">


            <div class="col-md-6">

              <div class="form-group">

                  <label class="control-label">Semestre Actual</label>

                  <input id="semestre" class="form-control form-control-lg capitalize" readonly>

              </div>

            </div>

            <div class="col-md-6">

              <div class="form-group">

                  <label class="control-label">Cambiar semestre</label>

                   <select name="semestre" class="form-control form-control-lg">
                     <option value="" disabled selected>Seleccione un semestre</option>
                     <option value="1">Primer semestre</option>
                     <option value="2">Segundo semestre</option>
                     <option value="3">Tercero semestre</option>
                     <option value="4">Cuarto semestre</option>
                     <option value="5">Quinto semestre</option>
                     <option value="6">Sexto semestre</option>
                     <option value="7">Septimo semestre</option>
                     <option value="8">Octavo semestre</option>
                     <option value="9">Noveno semestre</option>
                   </select>

              </div>

            </div>

            <div class="col-md-6">

              <div class="form-group">

                  <label class="control-label">Plan de estudio actual</label>

                  <input id="PlanEstudioActual" class="form-control form-control-lg capitalize" readonly>

              </div>

            </div>

            <div class="col-md-6">

              <div class="form-group">

                  <label class="control-label">Planes de estudio</label>

                  <select class="form-control form-control-lg" name="PlanEstudioId" id="PlanEstudioId">
                    <option value="" disabled selected>Elija un plan de estudio</option>
                    @foreach($planesEstudio as $key => $value)
                      <option value="{{$value['PlanEstudioId']}}">{{$value["Nombre"]}}</option>
                    @endforeach
                  </select>

              </div>

            </div>

            <div class="col-md-6">

              <div class="form-group">

                  <label class="control-label">Grupo actual</label>

                  <input id="EncGrupoActual" class="form-control form-control-lg capitalize" readonly>

              </div>

            </div>

            <div class="col-md-6">

              <div class="form-group">

                  <label class="control-label">Grupos</label>

                  <select class="form-control form-control-lg" name="EncGrupoId" id="EncGrupoId">
                    <option value="" disabled selected>Elija un grupo</option>
                    @foreach($EncGrupos as $key => $value)
                      <option value="{{$value['EncGrupoId']}}">{{$value["Nombre"]}}</option>
                    @endforeach
                  </select>

              </div>

            </div>

            <div class="col-md-6">

              <div class="form-group">

                  <label class="control-label">Matricula actual</label>

                  <input id="matriculaActual" class="form-control form-control-lg capitalize" readonly>

              </div>

            </div>

            <div class="col-md-6">

              <div class="row">

                  <div class="form-group">

                      <label class="control-label">Matricula generada</label>

                      <input id="matriculaGenerada" name="matriculaGenerada" class="form-control form-control-lg capitalize" readonly>

                  </div>

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
                <button class="btn btn-success" type="submit" id="button"><i class="fa fa-check"></i> Guardar</button>
              </div>

            </div>

        </div>

      </form>

    </div>

    </div>

  </div>

</div>

<script src="{{ asset('js/admin/alumn.js')}}"></script>

@stop
