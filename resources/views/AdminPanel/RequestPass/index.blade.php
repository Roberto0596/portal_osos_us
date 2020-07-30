@extends('AdminPanel.main')

@section('content-admin')

<div class="content-wrapper">

  <section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1>Soliciudes Para Reiniciar Contraseñas</h1>

        </div>

        <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right">

            <li class="breadcrumb-item"><a href="#">Home</a></li>

            <li class="breadcrumb-item active"><a href="#">Reiniciar Contraseñas</a></li>

          </ol>

        </div>

      </div>

    </div>

  </section>

  <section class="content">

    <div class="card">

      <div class="card-body">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        
        <table class="table table-bordered table-hover tableResetPass">

          <thead>

            <tr>
              <th style="width: 10px">#</th>
              <th>Nombre(s)</th>
              <th>Apellido(s)</th>
              <th>Email</th>
              <th>Fecha de registro</th>
              <th>Acciones</th>
              <!-- <th>Acciones</th> -->
            </tr>

          </thead>

        </table>

      </div>

    </div>

  </section>

</div>

<script src="{{ asset('js/admin/resetPass.js')}}"></script>




@stop
