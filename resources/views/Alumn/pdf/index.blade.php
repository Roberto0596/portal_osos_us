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
          
          <h1>Mis Documentos por Imprimir</h1>
          
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

        <div class="pull-left">

          <ul class="nav nav-tabs" id="myTab" role="tablist">

            <li class="nav-item">

              <a class="nav-link " id="document" data-toggle="tab" href="#document-panel" role="tab" aria-controls="documents" aria-selected="true">Document</a>

            </li>

            <li class="nav-item">

              <a class="nav-link active" id="document-inscription" data-toggle="tab" href="#inscription-panel" role="tab" aria-controls="documents_inscription" aria-selected="false">Documentos de inscripcion</a>

            </li>

          </ul>

        </div>

      </div>

    </div>

    <div class="card">

      <div class="card-body">

        <div class="tab-content" id="myTabContent">

          <div class="tab-pane fade show" id="document-panel" role="tabpanel" aria-labelledby="home-tab">

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

          <div class="tab-pane fade show active" id="inscription-panel" role="tabpanel" aria-labelledby="home-tab">

            @php
              $user = current_user();
              $acta = validateDocumentInscription($user->id,"acta de nacimiento");
              $kardex = validateDocumentInscription($user->id,"kardex");
              $certificado = validateDocumentInscription($user->id,"certificado");
            @endphp

            <div class="row">
            
              <div class="col-md-4 col-sm-12">

                <div class="small-box {{$acta?'bg-success':'bg-danger'}}">

                  <div class="inner">

                    <h3>Acta de nacimiento</h3>

                    <p>No has registrado tu acta</p>

                  </div>

                  <div class="icon">

                    <i class="fa fa-file"></i>

                  </div>

                  <a data-toggle="modal" data-target="#modalDocumentos"  class="small-box-footer pointer-link open-modal" name-document = "acta de nacimiento">Subir <i class="fas fa-arrow-circle-right"></i></a>

                </div>
                
              </div>

              <div class="col-md-4 col-sm-12">

                <div class="small-box {{$kardex?'bg-success':'bg-danger'}}">

                  <div class="inner">

                    <h3>Kardex</h3>

                    <p>No has registrado tu kardex</p>

                  </div>

                  <div class="icon">

                    <i class="fa fa-file"></i>

                  </div>

                  <a data-toggle="modal" data-target="#modalDocumentos" class="small-box-footer pointer-link open-modal" name-document = "kardex">Subir <i class="fas fa-arrow-circle-right"></i></a>

                </div>
                
              </div>

              <div class="col-md-4 col-sm-12">

                <div class="small-box {{$certificado?'bg-success':'bg-danger'}}">

                  <div class="inner">

                    <h3>Certificado</h3>

                    <p>No has registrado tu certificado</p>

                  </div>

                  <div class="icon">

                    <i class="fa fa-file"></i>

                  </div>

                  <a data-toggle="modal" data-target="#modalDocumentos" class="small-box-footer pointer-link open-modal" name-document = "certificado">Subir <i class="fas fa-arrow-circle-right"></i></a>

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

    <div class="modal-content">

      <div class="modal-header">

        <h3>Subir un documento</h3>

      </div>
        
        <div class="modal-body">

          <div class="container">

            <div class="row">
              <div class="col-md-12">
                  <p>ADVERTENCIA, estos documentos solo se pueden subir en formato PDF</p>
                </div>
            </div>

            <form action="/file-upload" enctype="multipart/form-data" class="dropzone"  id="my-awesome-dropzone">
                
              {{ csrf_field() }}
                
              <input type="hidden" id="name-document" name="name-document">

              <div class="dz-message needsclick">
                  <strong>Arrastra archivos a cualquier lugar para subirlos.</strong><br /><br />
                  <span class="note needsclick">
                  <span class="glyphicon glyphicon-open" aria-hidden="true" style="font-size:60px;"></span>
                  </span>
              </div>
                
            </form>

          </div>  

        </div>

    </div>

  </div>

</div>

<script type="text/javascript">
  $("#my-awesome-dropzo").dropzone({ 
    url: "/file/post",
    uploadMultiple: false,
    maxFiles: 1,
    acceptedFiles: "application/pd"
  });
</script>

<script src="{{ asset('js/alumn/pdf.js')}}"></script>

@stop
