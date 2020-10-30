@extends('AdminPanel.main')

@section('content-admin')

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Inscripciones <small>fallidas</small></h1>
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

        <table class="table table-bordered table-hover tableFailed">

          <thead>

            <tr>
              <th style="width: 10px">#</th>
              <th>Alumno</th>
              <th>Periodo</th>
              <th>Mensaje</th>
              <th>Status</th>
              <th>Fecha</th>
              <th>Acciones</th>
            </tr>

          </thead>

        </table>

      </div>

    </div>

  </section>

</div>

<div class="modal fade" id="modalFix">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header">

        <h3>Corregir Inscripción</h3>

      </div>

      <form action="{{ route('admin.failed.save') }}" method="post">

        {{ csrf_field() }}
        
        <div class="modal-body">
              
            <div class="row">

              <input type="hidden" name="_token" value="{{ csrf_token() }}" id="tokenModal">
              <input type="hidden" name="failedId" id="failedId">

              <div class="col-md-12">

                <label class="label-style" for="nombre">Seleccione un semestre</label>

                <div class="input-group mb-3">

                    <div class="input-group-prepend">

                      <span class="input-group-text"><i class="fas fa-th"></i></span>

                    </div>

                    <select name="semestre" id="semestre" class="form-control form-control-lg">
                        <option value="">Seleccione un semestre</option>
                        <option value="1">Primero</option>
                        <option value="2">Segundo</option>
                        <option value="3">Tercero</option>
                        <option value="4">Cuarto</option>
                        <option value="5">Quinto</option>
                        <option value="6">Sexto</option>
                        <option value="7">Septimo</option>
                        <option value="8">Octavo</option>
                        <option value="9">Noveno</option>
                    </select>

                </div>

              </div>

              <div class="col-md-12">

                <label class="label-style" for="nombre">Seleccione un grupo</label>

                <div class="input-group mb-3">

                    <div class="input-group-prepend">

                      <span class="input-group-text"><i class="fas fa-th"></i></span>

                    </div>

                    <select name="encGrupo" id="encGrupo" class="form-control form-control-lg">
                        <option value="" selected>Seleccione un grupo</option>
                    </select>

                </div>

              </div>
              
            </div>

            <div class="row">

              <div class="col-md-6">

                <div class="form-group">

                    <button class="btn btn-danger" data-dismiss="modal" id="dimiss" style="width: 100%"><i class="fa fa-times"></i> Cancelar</button>
                  
                </div>

              </div>

              <div class="col-md-6">

                <div class="form-group">

                    <button class="btn btn-success" style="width: 100%"><i class="fa fa-check"></i> Guardar</button>
                  
                </div>

              </div>

            </div>
              
        </div>

      </form>

    </div>

  </div>

</div>
<script src="{{ asset('js/admin/failed-register.js')}}"></script>

@stop
