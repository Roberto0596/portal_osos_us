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

  <div class="card-body">

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

<script src="{{ asset('js/library/debit.js')}}"></script>

@stop
