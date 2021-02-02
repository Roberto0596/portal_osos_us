@extends('Alumn.main')

@section('content-alumn')

<div class="content-wrapper">

    <section class="content-header">
    
        <div class="container-fluid">
          
          <div class="row mb-2">
            
            <div class="col-sm-6">
              
              <h1>Mis Recibos</h1>
              
            </div>
            
            <div class="col-sm-6">
              
              <ol class="breadcrumb float-sm-right">
                
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Recibos</a></li>
                
              </ol>
              
            </div>
            
          </div>
          
        </div>
        
      </section>

 
  <section class="content">

  
    <div class="card">

      <div class="card-body">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">

        <table class="table table-bordered table-hover dt-responsive tableTickets">

          <thead>

            <tr>
              <th style="width: 10px">#</th>
              <th>Concepto</th>
              <th>Monto</th>
              <th>Tipo de Adeudo</th>
              <th>Fecha</th>
              <th>Acciones</th>
            </tr>

          </thead>

        </table>

      </div>

    </div>

  </section>

</div>
 
<script src="{{asset('js/alumn/ticket.js')}}"></script>
 
@stop