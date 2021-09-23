@extends('Alumn.main')

@section('content-alumn')

<div class="content-wrapper">

  <section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1>Selecciona tu carga academica</h1>

        </div>

        <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right">

            <button class="btn btn-success buttom-custom print">Imprimir</button>

          </ol>

        </div>

      </div>

    </div>

  </section>

  <section class="content">

    @if($instance)

    <div class="card card-success" style="height: 80vh;">

      <div class="card-header nav-custom-green">

        <h3 class="card-title">El sistema ha seleccionado el siguiente listado para ti</h3>

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

                       <input type="checkbox" class="checkasignatura" name="seleccionadas[]" id="{{$key}}" value="{{$value->detGrupoId}}">

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

      @else

      <form action="{{route('alumn.charge.finally')}}" method="post">
        
        {{ csrf_field() }} 

        <div class="card">

          <div class="card-header">
            <h5>Opps!, parece que ha ocurrido un problema al momento de generar tu carga academica.</h5>
          </div>
        
          <div class="card-body">

            <div style="text-align: justify;">
              <p>Esto puede deberse a las siguientes situaciones</p>
              <ul>
                <li>Hay una inconsistencia en cuanto al plan de estudio y las materias que se llevan en el.</li>
                <li>Hay una inconsistencia en cuanto al registro de tus calificaciones.</li>
              </ul>
              <p>En caso de que seas de nuevo ingreso, puede deberse a movimientos en los planes de estudio, esto provoca que no se genere bien la carga academica.</p>
              <p>Imprime esta pagina y enviasela a personal de servicios escolares para que seleccionen tu carga academica de manera manual, una disculpa por los inconvenientes. de igual manera, preciona el boton de guardar para finalizar tu proceso.</p>
              <div style="text-align: center">
                <h4><b>Matricula: </b>{{ current_user()->sAlumn->Matricula }}</h4>
                <h4><b>Alumno: </b>{{ current_user()->FullName }}</h4>
              </div>

              <center>
                <button class="btn btn-success buttom-custom print" type="button">Imprime este aviso</button><br>
                <button class="btn btn-success" style="margin-top: 1rem">Terminar proceso</button>
              </center>

            </div>

          </div>

        </div>
      </form>
        
      @endif

  </section>

</div>

<script>
  $(".print").click(function(){
    window.print();
  });
</script>

@stop
