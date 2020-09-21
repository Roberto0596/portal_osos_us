@extends('AdminPanel.main')

@section('content-admin')

<div class="content-wrapper">

  <section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1>Documentos de Alumnos</h1>

        </div>

        <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right">

            <li class="breadcrumb-item"><a href="#">Home</a></li>

            <li class="breadcrumb-item active"><a href="#">Documentos</a></li>

          </ol>

        </div>

      </div>

    </div>

  </section>

  <section class="content">

    <div class="card">

      <div class="card-body">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        
        <table class="table table-bordered table-hover tableDocuments" id="tableDocuments">

          <thead>

            <tr>
              <th style="width: 10px">#</th>
              <th>Matricula</th>
              <th>Alumno</th>
              <th style="width: 200px">Estado</th>
              <th>Documentos</th>
              
            </tr>

          </thead>

        </table>

      </div>

    </div>

  </section>

</div>

<div class="modal fade modalDocuments" id="modalDocuments" data-backdrop='static' data-keyboard=false>

    <div class="modal-dialog modal-lg">
  
      <div class="modal-content">
  
        <div class="modal-header">
  
          <h3>Documentos del Alumno </h3>
  
        </div>
          
          <div class="modal-body">           
                
              <div class="row">             
  
                <div class="col-md-12">
  
                  <input type="hidden" name="_token" value="{{ csrf_token() }}" id="tokenModal">
                  
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Nombre del documento</th>
                          <th>Ver</th>
                          <th>Modificar</th>
                        </tr>
                      </thead>
                      <tbody id="tbody-documents">
                        
                      </tbody>

                    </table>

                </div>
  
              </div>
  
              <div class="row">
  
                <div class="col-md-12">
  
                  <div class="form-group" id="pay-now" style="margin-top: 10vh;">
  
                      <button class="btn btn-success" data-dismiss="modal" id="dimiss">Cerrar</button>
                    
                  </div>
  
                </div>
  
              </div>
                
          </div>
  
      </div>
  
    </div>
  
  </div>


  <script src="{{ asset('js/admin/document.js')}}"></script>



@stop
