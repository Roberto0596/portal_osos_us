@extends('Alumn.main')

@section('content-alumn')

@php
  $box = $status?"bg-success":"bg-danger";
  $boxDocuments = count($documents) == 0?"bg-default":"bg-primary";
  $boxDebits = $debits == 0?"bg-default":"bg-warning";
  $styleBox = $debits == 0?"style='color:white;'":"";
  $boxCorreo = strpos(Auth::guard('alumn')->user()->email, "@unisierra.edu.mx")?"bg-info":"bg-danger";
  $boxTickets = count($tickets) == 0 ? "bg-default":"bg-success";
 
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

          <div class="col-md-12"> 

                <h4>Mi tablero</h4> 

          </div>

          <div class="col-md-3 col-sm-12">

            <div class="small-box {{$boxCorreo}}">

              <div class="inner">

                <h3>Mi perfil</h3>

                <p>{{Auth::guard("alumn")->user()->email}}</p>

              </div>

              <div class="icon">

                <i class="fa fa-envelope"></i>

              </div>

              @if(strpos(Auth::guard('alumn')->user()->email, "@unisierra.edu.mx"))
              <a href="{{route('alumn.user')}}" class="small-box-footer">Ver mi perfil<i class="fas fa-arrow-circle-right"></i></a>
              @else
              <a href="#" class="small-box-footer">Ir a perfil<i class="fas fa-arrow-circle-right"></i></a>
              @endif

            </div>
            
          </div>

          <div class="col-md-3 col-sm-12">

            <div class="small-box {{$box}}">

              <div class="inner">

                <h3>{{ $status }}</h3>

                <p>{{ $status != "Inscrito" ? "En proceso" : "Proceso terminado" }}</p>

              </div>

              <div class="icon">

                <i class="fa fa-user"></i>

              </div>

              <a href="{{isNoob(Auth::guard('alumn')->user()->id)}}" class="small-box-footer">{{ $status != "Inscrito" ? "Inscribirse ":"Gracias" }}<i class="fas fa-arrow-circle-right"></i></a>

            </div>
            
          </div>

          <div class="col-md-3 col-sm-12">

            <div class="small-box {{$boxDocuments}}">

              <div class="inner">

                <h3>Documentos</h3>

                <p>¡Revisalos!</p>

              </div>

              <div class="icon">

                <i class="fa fa-folder"></i>

              </div>

              <a href="{{route('alumn.documents')}}" class="small-box-footer">Ver<i class="fas fa-arrow-circle-right"></i></a>

            </div>
            
          </div>

          <div class="col-md-3 col-sm-12">

            <div class="small-box {{$boxDebits}}">

              <div class="inner">

                <h3 {{$styleBox}}>Adeudos</h3>

                <p {{$styleBox}}>Tienes: {{$debits}} adeudos</p>

              </div>

              <div class="icon">

                <i class="fa fa-file-invoice-dollar"></i>

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

    <div class="row">

      <div class="col-md-5">

        <div class="card">

          <div class="card-body">

            <div class="row">

              <div class="col-md-12">

                <div class="row">

                  <div class="col-md-12">

                    <h4>Mis Acciones</h4> 

                  </div>

                  <div class="col-md-12">

                    <div class="col-md-12 col-sm-12">

                      <div class="small-box bg-danger" style="background: #4e4e4e !important">

                        <div class="inner">

                          <h3>Reportar problema</h3>

                          <p>Ayudanos a mejorar</p>

                        </div>

                        <div class="icon">

                          <i class="fa fa-archive"></i>

                        </div>

                        <a data-toggle="modal" data-target="#modalProblems" style="cursor: pointer;" class="small-box-footer">Enviar problema<i class="fas fa-arrow-circle-right"></i></a>

                      </div>
                      
                    </div>

                  </div>

                </div>

              </div>

            </div>

          </div>

        </div>

      </div>

      <div class="col-md-7">

        <div class="card">

          <div class="card-body">

            <div class="row">

              <div class="col-md-12">

                <div class="row">

                  <div class="col-md-12">

                    <h4>Más</h4> 

                    <div class="row">
                        {{-- Tickets --}}

                      <div class="col-md-6 col-sm-12">

                        <div class="small-box {{$boxTickets}}">

                          <div class="inner">

                            <h3>Recibos</h3>

                            <p>¡Revisalos!</p>

                          </div>

                          <div class="icon">

                            <i class="fas fa-file-alt"></i>

                          </div>

                          <a href="{{ route('alumn.tickets')}}" class="small-box-footer">Ver &nbsp;<i class="fas fa-arrow-circle-right"></i></a>

                        </div>
                        
                      </div>




                      {{-- Academic Charge --}}

                      <div class="col-md-6 col-sm-12">

                        <div class="small-box bg-success">

                          <div class="inner">

                            <h3>Carga Académica</h3>

                            <p>¡Revisala!</p>

                          </div>

                          <div class="icon">

                            <i class="fas fa-book"></i>

                          </div>

                          <a href="{{route('alumn.academicCharge')}}" class="small-box-footer">Ver &nbsp;<i class="fas fa-arrow-circle-right"></i></a>

                        </div>
                        
                      </div>

                    </div>

                  </div>

                </div>

              </div>

            </div>

          </div>

        </div>

      </div>

    </div>

  </section>

</div>

<div class="modal fade" id="modalProblems">

  <div class="modal-dialog modal-lg">

    <div class="modal-content">

      <div class="modal-header">

        <h3>Problemas encontrados en el sistema</h3>

      </div>

      <form action="{{route('alumn.home.problem')}}" method="post" enctype="multipart/form-data">

        <div class="modal-body">
            
          {{ csrf_field() }}

          <div class="row">

            <div class="col-md-12">

              <div class="form-group">

              <div class="panel">Describe el problema</div>

                <textarea name="text" class="form-control form-control-lg" cols="30" rows="5" placeholder="Intente describir el problema, sea específico, intente ser lo mas conciso que pueda para poder ofrecerle una mejor ayuda." required></textarea>

              </div>

            </div>

          </div>
            
        </div>

        <div class="modal-footer justify-content">

          <div class="col-sm container-fluid">

            <div class="row">

              <div class="col-md-6 btn-group">
                  <button type="button" class="btn btn-danger .px-2 " data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
              </div>

              <div class="col-md-6 btn-group">
                <button class="btn btn-success" type="submit"><i class="fa fa-check"></i> Enviar</button>
              </div>

            </div>

        </div>

      </form>

    </div>

    </div>

  </div>

</div>

<script src="{{asset('js/alumn/home.js')}}"></script>

@stop
