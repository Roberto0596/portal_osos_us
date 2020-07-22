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

        <h3 class="card-title">Para dar de baja una materia debes comunicarte con servicios escolares</h3>

      </div>
      
      <div class="card-body scroll-charge">

        <div class="row">

          <div class="col-md-12">

            <form action="{{route('alumn.charge.save',$user)}}" method="post">

              {{ csrf_field() }}

            <input type="hidden" name="currentAsignatures" value="{{json_encode($asignatures,true)}}">

            <table class="table">

              <thead>

                <tr>

                  <th>Materia</th>

                  <th>Semestre</th>

                  <th>Plan de estudio</th>

                  <th>Profesor</th>

                </tr>

              </thead>

              <tbody>
              
              @foreach($asignatures as $key => $value)

              @php
               $planEstudio = selectSicoes("PlanEstudio","PlanEstudioId",$value["PlanEstudioId"])[0]["Nombre"];
               $teacher = selectSicoes("Profesor","ProfesorId",$value[12])[0]["Nombre"];
              @endphp

              <tr>

                <td>

                  <div class="form-group clearfix">

                    <div class="icheck-success d-inline">

<!--                       <input type="checkbox" class="checkasignatura" name="{{$key}}" id="{{$key}}" checked value="{{$value[11]}}"> -->

                      <label for="{{$key}}">
                          {{$value["Nombre"]}}
                      </label>
                      <input type="hidden" name="detgrupoid" value="{{$value[11]}}">

                    </div>

                  </div>

                </td>

                <td>{{$value["Semestre"]}}</td>

                <td>{{$planEstudio}}</td>

                <td>{{$teacher}}</td>

              </tr>               

              @endforeach

              </tbody>

            </table>

<!--             <div class="form-group row">

              <button type="submit" class="btn btn-warnign flotante" title="Guardar" style="background: green !important;bottom: 60px !important;"><i class="fa fa-save" style="color: white !important;"></i></button>

            </div> -->

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
