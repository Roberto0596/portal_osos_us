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
                      @php
                        $periods = selectSicoes("Periodo");
                      @endphp
                      @for ($i = count($periods) - 1; $i >= 0; $i--)
                      <option value="{{$periods[$i]['PeriodoId']}}">{{$periods[$i]['Clave']}}</option>
                      @endfor
                     
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
  
          <form method="post" action="#">
              
              {{ csrf_field() }}

              <input type="hidden" name="_token" value="{{ csrf_token() }}" id="tokenUpdate">
  
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
                                <i class="fas fa-ad"></i></span>
                            </div>

                          <input type="text" name="concept" placeholder="Concepto" class="form-control form-control-lg" required>

                        </div>
                    </div>
  
                  </div>
  
              </div>
  
              <div class="modal-footer justify-content">
  
                  <div class="col-sm container-fluid">
  
                      <div class="row">
  
                          <div class=" col-sm-6 btn-group">
  
                          <button id="cancel" type="button" class="btn btn-danger .px-2 " data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
  
                          </div>
  
                          <div class=" col-sm-6 btn-group">
  
                          <button type="submit" id="sale" class="btn btn-success .px-2"><i class="fa fa-check"></i> Guardar</button>
                          
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
