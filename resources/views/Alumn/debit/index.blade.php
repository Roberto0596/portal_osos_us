@extends('Alumn.main')

@section('content-alumn')

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

          <h1>Mis Adeudos</h1>

        </div>

        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('alumn.home')}}">Home</a></li>
            <li class="breadcrumb-item active">Mis Adeudos</li>
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
              <th>Fecha</th>
              <th>Acciones</th>
            </tr>

          </thead>

        </table>

      </div>

    </div>

  </section>

</div>

<script src="{{ asset('js/alumn/debit.js')}}"></script>

@stop