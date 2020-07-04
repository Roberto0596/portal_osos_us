@extends('Alumn.main')

@section('content-alumn')

@php
  $box = $status?"bg-success":"bg-danger";
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

          <div class="col-lg-3 col-6">

            <div class="small-box {{$box}}">

              <div class="inner">

                <h3>{{$status?"Inscrito":"Inscribirse"}}</h3>

                <p>{{$status?"Proceso terminado":"Aun no te inscribes"}}</p>

              </div>

              <div class="icon">

                <i class="fa fa-user"></i>

              </div>

              <a href="{{route('alumn.form')}}" class="small-box-footer">Incribirme<i class="fas fa-arrow-circle-right"></i></a>

            </div>
            
          </div>

        </div>

      </div>

    </div>

  </section>

</div>

@stop
