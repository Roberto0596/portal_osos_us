@extends('Alumn.main')

@section('content-alumn')

<div class="content-wrapper">

  <section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1>Esta sera tu carga academica</h1>

        </div>

        <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right">

            <button class="btn btn-success buttom-custom" id="print">Imprimir</button>

          </ol>

        </div>

      </div>

    </div>

  </section>

  <section class="content">

    <div class="card card-success" style="height: 80vh;">

      <div class="card-header nav-custom-green">

        <h3 class="card-title">El sistema ha seleccionado estas materias para ti</h3>

      </div>
      
      <div class="card-body scroll-charge">

        <div class="row">

          <div class="col-md-12">

            <form action="{{route('alumn.charge.save')}}" method="post">

              {{ csrf_field() }}

            <table class="table">

              <thead>

                <tr>

                  <th>Materia</th>

                  <th>Semestre</th>

                  <th>Alumno</th>

                  <th>Profesor</th>

                </tr>

              </thead>

              <tbody>
              
              @foreach($instance as $key => $value)
              <tr>

                <td>

                  <div class="form-group clearfix">

                    <div class="icheck-success d-inline">

                     <input type="checkbox" class="checkasignatura" name="seleccionadas[]" id="{{$key}}" checked value="{{$value->detGrupoId}}">

                      <label for="{{$key}}">
                          {{$value->materia}}
                      </label>

                    </div>

                  </div>

                </td>

                <td>{{$value->semestre}}</td>

                <td>{{$value->nombre}}</td>

                <td>{{$value->nombreProfesor}}</td>

              </tr>               

              @endforeach

              </tbody>

            </table>

            @php
              $id_alumno = Auth::guard("alumn")->user()->id_alumno;
              $inscription = getLastThing("Inscripcion","AlumnoId",$id_alumno,"InscripcionId");
            @endphp            

            <div class="form-group row">

              <button type="submit" class="btn btn-warnign flotante" title="Guardar" style="background: green !important;bottom: 60px !important;"><i class="fa fa-save" style="color: white !important;"></i></button>

            </div>

            </form>

          </div>

        </div>

      </div>

      <div class="card-footer footer-orange">
        No te apresures, decide bien, eso te ayudara en tu carrera
      </div>

    </div>

  </section>

</div>

<script>
  $("#print").click(function(){
    window.print();
  });
</script>

@stop
