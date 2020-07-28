@extends('AdminPanel.main')

@section('content-admin')

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Problemas</h1>
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

        Estos son los problemas encontrados por los alumnos.

      </div>

    </div>

    <div class="card">

      <div class="card-body">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">

        <table class="table table-bordered table-hover tableProblem">

          <thead>

            <tr>
              <th style="width: 10px">#</th>
              <th>Matricula</th>
              <th>Alumno</th>
              <th>Telefono</th>
              <th>Email</th>
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

<div class="modal fade" id="modalProblems" data-backdrop='static' data-keyboard=false>

  <div class="modal-dialog modal-lg">

    <div class="modal-content">

      <div class="modal-header">

        <h3>Problemas encontrados en el sistema</h3>

      </div>
        
        <div class="modal-body">
              
            <div class="row">

              <div id="loader" class="loader"></div>

              <div class="col-md-12">

                <input type="hidden" name="_token" value="{{ csrf_token() }}" id="tokenModal">
                
                <p id="parraf-problem" style="text-align: justify;">
                  
                </p>

              </div>

            </div>

            <div class="row">

              <div class="col-md-12">

                <div class="form-group" id="pay-now" style="margin-top: 10vh;">

                    <button class="btn btn-success" data-dismiss="modal" id="dimiss">Aceptar</button>
                  
                </div>

              </div>

            </div>
              
        </div>

    </div>

  </div>

</div>
<script src="{{ asset('js/admin/problem.js')}}"></script>

@stop
