@extends('DepartamentPanel.main')

@section('content-departament')

<div class="content-wrapper">

  <section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1>Registro de aulas</h1>

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

        <table class="table table-bordered table-hover dt-responsive">

          <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Area</th>
              <th>Nombre</th>
              <th>Codigo</th>
              <th>Numero</th>
              <th>Estado</th>
              <th>Acciones</th>             
            </tr>  
          </thead>

          <tbody>
            
          </tbody>

        </table>

      </div>

    </div>

  </section>

</div>

<script>
  $(".table").DataTable();
</script>

@stop
