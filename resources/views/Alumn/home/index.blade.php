@extends('Alumn.main')

@section('content-alumn')

@php
  $box = $status?"bg-success":"bg-danger";
  $boxDocuments = count($documents) == 0?"bg-default":"bg-primary";
  $boxDebits = $debits == 0?"bg-default":"bg-warning";
  $styleBox = $debits == 0?"style='color:white;'":"";
@endphp

<div class="content-wrapper">
  
  <section class="content-header">
    
    <div class="container-fluid">
      
      <div class="row mb-2">
        
        <div class="col-sm-6">
          
          <h1>¡Bienvenido!</h1>
          
        </div>
        
        <div class="col-sm-6">
          
          <ol class="breadcrumb float-sm-right">
            
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            
          </ol>
          
        </div>
        
      </div>
      
    </div>
    
  </section>

  <section class="content">

    <div class="card">

      <div class="card-body">

        <div class="row">

          <div class="col-md-3 col-sm-12">

            <div class="small-box {{$box}}">

              <div class="inner">

                <h3>{{$status?"Inscrito":"Inscribirse"}}</h3>

                <p>{{$status?"Proceso terminado":"Aun no te inscribes"}}</p>

              </div>

              <div class="icon">

                <i class="fa fa-user"></i>

              </div>

              <a href="{{route('alumn.form')}}" class="small-box-footer">{{!$status?"Inscribirse ":"Ver carga "}}<i class="fas fa-arrow-circle-right"></i></a>

            </div>
            
          </div>

          <div class="col-md-3 col-sm-12">

            <div class="small-box {{$boxDocuments}}">

              <div class="inner">

                <h3>Documentos</h3>

                <p>{{count($documents) == 0?"No hay documentos":"tienes algunos documentos"}}</p>

              </div>

              <div class="icon">

                <i class="fa fa-user"></i>

              </div>

              @if(count($documents) != 0)
              <a href="{{route('alumn.documents')}}" class="small-box-footer">Imprimir<i class="fas fa-arrow-circle-right"></i></a>
              @else
              <a href="#" class="small-box-footer">Vacio<i class="fas fa-arrow-circle-right"></i></a>
              @endif

            </div>
            
          </div>

          <div class="col-md-3 col-sm-12">

            <div class="small-box {{$boxDebits}}">

              <div class="inner">

                <h3 {{$styleBox}}>Adeudos</h3>

                <p {{$styleBox}}>Tienes: {{$debits}} adeudos</p>

              </div>

              <div class="icon">

                <i class="fa fa-user"></i>

              </div>

              @if($debits != 0)
              <a href="{{route('alumn.debit')}}" class="small-box-footer">Ver<i class="fas fa-arrow-circle-right"></i></a>
              @else
              <a href="#" class="small-box-footer">Vacio<i class="fas fa-arrow-circle-right"></i></a>
              @endif

            </div>
            
          </div>

        </div>

      </div>

    </div>

  </section>

</div>

@stop
