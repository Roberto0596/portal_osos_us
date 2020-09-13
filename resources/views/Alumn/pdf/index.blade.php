@extends('Alumn.main')

@section('content-alumn')

<style type="text/css">
  .pointer-link
  {
    cursor: pointer;
  }
</style>

<div class="content-wrapper">
  
  <section class="content-header">
    
    <div class="container-fluid">
      
      <div class="row mb-2">
        
        <div class="col-sm-6">
          
          <h1>Documentos</h1>
          
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

  @php
   if(session()->has('tab'))
   {
      $aux = session()->get('tab');
   } else {
      $aux = 0;
   }
  @endphp

  <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">

  <section class="content">

    <div class="card">

      <div class="card-body">

        <div class="pull-left">

          <ul class="nav nav-tabs" id="myTab" role="tablist">

            <li class="nav-item">

              <a class="nav-link {{$aux == 0?'active':''}} tap-change" id="document" data-toggle="tab" href="#document-panel" role="tab" aria-controls="documents" aria-selected="true" data-value="0">Expediente</a>

            </li>

            <li class="nav-item">

              <a class="nav-link {{$aux == 1?'active':''}} tap-change" id="document-inscription" data-toggle="tab" href="#inscription-panel" role="tab" aria-controls="documents_inscription" aria-selected="false" data-value="1">Cargar Documentos</a>

            </li>

          </ul>

        </div>

      </div>

    </div>


    <div class="tab-content" id="myTabContent">

      <div class="tab-pane fade show {{$aux == 0?'active':''}}" id="document-panel" role="tabpanel" aria-labelledby="home-tab">

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

      </div>

      <div class="tab-pane fade show {{$aux == 1?'active':''}}" id="inscription-panel" role="tabpanel" aria-labelledby="home-tab">

        <div class="card collapsed-card">

          <div class="card-header">

            <h3 class="card-title">Ayuda</h3>

            <div class="card-tools">

              <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fas fa-minus"></i></button>

            </div>

          </div>

          <div class="card-body">
            <p>Hola, {{ucfirst(strtolower(current_user()->name))}}, podras subir tus documentos oficiales en este apartado, hay tres estados posibles</p>

<!--             <div class="row">
              <div class="col-md-4"> -->
                <div class="callout callout-danger">
                  <h5>Rojo</h5>
                  <p>este estado es cuando todavia no esta el documento, podras subirlo en la parte de abajo, dando clic en su ficha.</p>
                </div>
<!--               </div> -->
<!--               <div class="col-md-4"> -->
                <div class="callout callout-warning">
                  <h5>Amarillo</h5>
                  <p>este estado es cuando ya tenemos el documento, pero falta que sea validado por el Departamento de Servicios Escolares, en este estado es posible que se te pida que vuelvas a subir el documento, si no cumple con los lineamientos de la Universidad.</p>
                </div>
<!--               </div>
              <div class="col-md-4"> -->
                <div class="callout callout-success">
                  <h5>Verde</h5>
                  <p>este estado es cuando ya esta el documento arriba y el Departamento de Servicios Escolares hizo la validación y se aprobó, entonces las cajas de abajo apareceran en verde.</p>
                </div>
<!--               </div>
            </div>  -->              
                
          </div>

        </div>

        <div class="card collapsed-card">

          <div class="card-header">

            <h3 class="card-title">Fichas</h3>

            <div class="card-tools">

              <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fas fa-minus"></i></button>

            </div>
            
          </div>

          <div class="card-body">

            @php
              $user = current_user();
              $acta = explode("|",validateDocumentInscription($user->id,"acta de nacimiento"));
              $fotografia = explode("|",validateDocumentInscription($user->id,"fotografia"));
              $certificado = explode("|",validateDocumentInscription($user->id,"certificado"));
              $curp = explode("|",validateDocumentInscription($user->id,"curp"));
              $imss = explode("|",validateDocumentInscription($user->id,"imss"));
            @endphp

            <div class="row">
            
              <div class="col-md-4 col-sm-12">

                <div class="small-box {{$acta[0]}}" style="color: white !important;">

                  <div class="inner">

                    <h3>Acta de nacimiento</h3>

                    <p>{{$acta[1]}}</p>

                  </div>

                  <div class="icon">

                    <i class="fa fa-file"></i>

                  </div>

                  @if($acta[0] != 'bg-success')

                  <a data-toggle="modal" data-target="#modalDocumentos"  class="small-box-footer pointer-link open-modal" name-document = "acta de nacimiento">Subir <i class="fas fa-arrow-circle-right"></i></a>

                  @endif

                </div>
                
              </div>

              <div class="col-md-4 col-sm-12">

                <div class="small-box {{$fotografia[0]}}" style="color: white !important;">

                  <div class="inner">

                    <h3>Fotografía</h3>

                    <p>{{$fotografia[1]}}</p>

                  </div>

                  <div class="icon">

                    <i class="fa fa-file"></i>

                  </div>

                  @if($fotografia[0] != 'bg-success')

                  <a data-toggle="modal" data-target="#modalDocumentos" class="small-box-footer pointer-link open-modal" name-document = "fotografia">Subir <i class="fas fa-arrow-circle-right"></i></a>

                  @endif

                </div>
                
              </div>

              <div class="col-md-4 col-sm-12">

                <div class="small-box {{$certificado[0]}}" style="color: white !important;">

                  <div class="inner">

                    <h3>Certificado</h3>

                    <p>{{$certificado[1]}}</p>

                  </div>

                  <div class="icon">

                    <i class="fa fa-file"></i>

                  </div>

                  @if($certificado[0] != 'bg-success')

                  <a data-toggle="modal" data-target="#modalDocumentos" class="small-box-footer pointer-link open-modal" name-document = "certificado">Subir <i class="fas fa-arrow-circle-right"></i></a>

                  @endif

                </div>
                
              </div>

              <div class="col-md-6 col-sm-12">

                <div class="small-box {{$curp[0]}}" style="color: white !important;">

                  <div class="inner">

                    <h3>CURP</h3>

                    <p>{{$curp[1]}}</p>

                  </div>

                  <div class="icon">

                    <i class="fa fa-file"></i>

                  </div>

                  @if($curp[0] != 'bg-success')

                  <a data-toggle="modal" data-target="#modalDocumentos" class="small-box-footer pointer-link open-modal" name-document = "CURP">Subir <i class="fas fa-arrow-circle-right"></i></a>

                  @endif

                </div>
                
              </div>

              <div class="col-md-6 col-sm-12">

                <div class="small-box {{$imss[0]}}" style="color: white !important;">

                  <div class="inner">

                    <h3>IMSS</h3>

                    <p>{{$imss[1]}}</p>

                  </div>

                  <div class="icon">

                    <i class="fa fa-file"></i>

                  </div>

                  @if($imss[0] != 'bg-success')

                  <a data-toggle="modal" data-target="#modalDocumentos" class="small-box-footer pointer-link open-modal" name-document = "IMSS">Subir <i class="fas fa-arrow-circle-right"></i></a>

                  @endif

                </div>
                
              </div>

            </div>

          </div>

        </div>

      </div>

    </div>

  </section>

</div>

<div class="modal fade" id="modalDocumentos">

  <div class="modal-dialog modal-lg">

    <div class="modal-content" style="border-radius: 20px;">

      <div class="modal-header" style="border-radius: 20px;">

        <h3>Subir un documento</h3>

      </div>
        
        <div class="modal-body">

          <div class="container">

            <div class="row">
              <div class="col-md-12">
                  <p>ADVERTENCIA, estos documentos solo se pueden subir en formato PDF</p>
                </div>
            </div>

            <!-- <form action="/file-upload" enctype="multipart/form-data" class="dropzone"  id="my-awesome-dropzone"> -->
              <form action="{{route('alumn.save.document.inscription')}}" method="post" enctype="multipart/form-data">
                
              {{ csrf_field() }}
                
              <input type="hidden" id="name-document" name="name-document">

              <div class="row">

                <div class="col-md-12">
                  
                  <div class="form-group">

                  <div class="panel">SUBIR DOCUMENTO</div>

                    <input accept="application/pdf" type="file" name="file-document" id="file-document" required>

                  </div>

                </div>

              </div>

              <!-- <div class="dz-message needsclick">
                  <strong>Arrastra archivos a cualquier lugar para subirlos.</strong><br /><br />
                  <span class="note needsclick">
                  <span class="glyphicon glyphicon-open" aria-hidden="true" style="font-size:60px;"></span>
                  </span>
              </div> -->

              <div class="row">

              <div class="col-md-12">

                <div class="form-group" id="pay-now" style="margin-top: 10vh;">

                  <div class="row">
                    <div class="col-md-6">
                      <button type="button" class="btn btn-danger" style="width: 100%;" data-dimiss="modal"><i class="fas fa-times"></i> Cancelar</button>
                    </div> 
                    <div class="col-md-6">
                      <button class="btn btn-success" style="width: 100%;" type="submit"><i class="fas fa-check"></i> subir</button>
                    </div>  
                  </div>
                  
                </div>

              </div>

            </div>
                
            </form>

          </div>  

        </div>

    </div>

  </div>

</div>

<script type="text/javascript">
  // $("#my-awesome-dropzo").dropzone({ 
    // url: "/file/post",
    // uploadMultiple: false,
    // maxFiles: 1,
    // acceptedFiles: "application/pd"
  // });
</script>

<script src="{{ asset('js/alumn/pdf.js')}}"></script>

@stop
