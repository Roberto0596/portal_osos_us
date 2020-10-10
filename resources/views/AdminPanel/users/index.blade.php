@extends('AdminPanel.main')

@section('content-admin')

<div class="content-wrapper">

  <section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1>Usuarios Registrados</h1>

        </div>

        <div class="col-sm-6">

          <div class="pull-right" style="float: right;">
            <a href="{{route('admin.users.create')}}" class="btn btn-success"><i class="fa fa-user" aria-hidden="true"></i> Crear usuario</a>
          </div>

        </div>

      </div>

    </div>

  </section>

  <section class="content">

    <div class="card" id="card">

      <div class="card-body">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">

        <table class="table table-bordered table-hover tableUsers">

          <thead>

            <tr>
              <th style="width: 10px">#</th>
              <th>Nombre(s)</th>
              <th>Apellido(s)</th>
              <th>Email</th>
              <th>Foto</th>
              <th>Area</th>
              <th>Fecha de registro</th>
              <th>Acciones</th>
            </tr>

          </thead>

        </table>

      </div>

    </div>

  </section>

</div>

<script src="{{ asset('js/admin/users.js')}}"></script>

@stop
