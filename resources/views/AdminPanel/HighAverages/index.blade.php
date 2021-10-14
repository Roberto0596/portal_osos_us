@extends('AdminPanel.main')

@section('content-admin')

<div class="content-wrapper">

  <section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1>Promedios Altos</h1>

        </div>

        <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right">

            <li class="breadcrumb-item"><a href="#">Home</a></li>

            <li class="breadcrumb-item active"><a href="#">promedios altos</a></li>

          </ol>

        </div>

      </div>

    </div>

  </section>

  <section class="content">

    <div class="card">

      <div class="card-body">

        <div class="row">

            <div class="col-md-3">

              <div class="form-group">
    
                  <label for="period" data-alias="periodo" class="control-label">Periodo</label>
    
                  <select id="period" name="period"  isnullable="no" class="form-control select2">

                      @foreach(getPeriodos() as $item)
                      <option value="{{ $item->PeriodoId }}">{{ $item->Clave }}</option>
                      @endforeach
                     
                  </select>
    
              </div>
              
            </div>

            <div style="padding-top: 2rem; margin-left:1rem;">
                <button data-toggle="modal" data-target="#modalHighAverages" class="btn btn-success" id="create">Agregar Alumno</button>
            </div>

        </div>      

        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        
        <table class="table table-bordered table-hover tableHighAverges" id="HighAverges">

          <thead>

            <tr>
              <th style="width: 10px">#</th>
              <th>Matricula</th>
              <th>Nombre</th>
              <th style="width: 20px">Eliminar</th>
            </tr>

          </thead>

        </table>

      </div>

    </div>

  </section>

</div>

<div class="modal fade" id="modalHighAverages">

    <div class="modal-dialog modal-lg">
  
      <div class="modal-content">
  
          <form id="addAlumn" method="post" action="{{ route('admin.high-averages.add') }}">
              
              {{ csrf_field() }}

              <input type="hidden" name="_token" value="{{ csrf_token() }}" id="tokenSearch">
              <input type="hidden" name="period" value="" id="periodId">
             
  
              <div class="modal-header">
  
                  <h4 class="modal-title">Agregar Alumno</h4>
  
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
  
              </div>
          
              <div class="modal-body">
  
                  <div class="row">

                    <div class="col-md-12">           
  
                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-search"></i></span>
                            </div>

                          <input type="text" name="enrollment" placeholder="Buscar Alumno por matricula" 
                          class="form-control form-control-lg" required id="searchAlumn">

                        </div>


                        <div class="input-group mb-3">

                          <table class="table table-bordered table-hover tableAlumns" id="alumnsTable">

                            <thead>
                  
                              <tr>
                                <th style="width: 10px">#</th>
                                <th>Matricula</th>
                                <th>Nombre</th>
                                <th style="width: 20px">Agregar</th>
                              </tr>
                  
                            </thead>

                            <tbody id="tableBody">

                            </tbody>
                  
                          </table>

                        </div>
                    </div>
  
                  </div>
  
            </div>
  
         </form>
  
      </div>
  
    </div>
  </div>

  


  <script src="{{ asset('js/admin/highAverages.js')}}"></script>





@stop
