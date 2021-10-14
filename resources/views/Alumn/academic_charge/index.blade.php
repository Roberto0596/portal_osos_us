@extends('Alumn.main')

@section('content-alumn')

<div class="content-wrapper">

    <section class="content-header">
    
        <div class="container-fluid">
          
          <div class="row mb-2">
            
            <div class="col-sm-6">
              
              <h1>Mi Carga Académica</h1>
              
            </div>
            
            <div class="col-sm-6">
              
              <ol class="breadcrumb float-sm-right">
                
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active"><a href="#">carga académica</a></li>
                
              </ol>
              
            </div>
            
          </div>
          
        </div>
        
      </section>

 
  <section class="content" >

  
    <div class="card" >

      <div class="row">

        <div class="col">
            
          <div class="card-header border-0">
                
            <div class="row">

              <div class="col-md-3">

                <div class="form-group">

                  <label for="period" data-alias="periodo" class="control-label">Periodo</label>

                  <select id="period" name="period"  isnullable="no" class="form-control select2">

                    @foreach($periods as $item)
                    <option value="{{$item->Clave}}">{{ $item->Clave }}</option>
                    @endForeach
                    
                  </select>

                </div>

              </div>

              
              <div style="padding-top: 2rem; margin-left:1rem;">
                <button class="btn btn-success" id="print">Exportar a PDF</button>
              </div>

            </div>

          </div>

            <div class="table-responsive" id="sectionToPrint">

              <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">

              <table class="table align-items-center table-flush">

                <thead class="thead-light">

                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Asignatura</th>
                    <th scope="col">Semestre</th>
                    <th scope="col">Profesor</th>
                    <th scope="col">Calificación</th>
                  </tr>

                </thead>

                <tbody id="tableBody"></tbody>
              </table>

            </div>
            
        </div>

      </div>

    </div>

  </section>

</div>
 
<script src="{{asset('js/alumn/academic_charge.js')}}"></script>
 
@stop