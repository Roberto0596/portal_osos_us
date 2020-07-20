@extends('Alumn.main')

@section('content-alumn')

<div class="content-wrapper">
  
  <section class="content-header">
    
    <div class="container-fluid">
      
      <div class="row mb-2">
        
        <div class="col-sm-6">
          
          <h1>Mis documentos por imprimir</h1>
          
        </div>
        
        <div class="col-sm-6">
          
          <ol class="breadcrumb float-sm-right">
            
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active"><a href="#">documentos</a></li>
            
          </ol>
          
        </div>
        
      </div>
      
    </div>
    
  </section>

  <section class="content">

    <div class="card">
      <div class="card-header">
        Nota:
      </div>
      <div class="card-body">
        <p>Estos documentos sólo se pueden imprimir en una sóla ocasión, pues llevan registro de fecha. Si quieres conservarlos, asegurate de guardarlos como PDF.</p>
      </div>
    </div>

    <div class="card">

      <div class="card-body">

      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">

        <table class="table table-bordered table-hover tableDocuments">

          <thead>

            <tr>
              <th style="width: 10px">#</th>
              <th>Nombre</th>
              <th>Acciones</th>
            </tr>

          </thead>

        </table>

      </div>

    </div>

  </section>

</div>

<script src="{{ asset('js/alumn/pdf.js')}}"></script>

@stop
