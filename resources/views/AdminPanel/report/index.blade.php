@extends('AdminPanel.report.layout-report')

@section('report-content')  

<div class="card">

    <div class="card-header">
      <button class="btn btn-success" id="print">Imprimir</button>
    </div>

      <div class="card-body">

        <table class="table table-bordered table-hover tableReport">

          <thead>

            <tr>
              <th>Clave</th>
              <th>Matricula</th>
              <th>Alumno</th>
              <th>Telefono</th>
              <th>Email</th>
              <th>Estado</th>
            </tr>

          </thead>

          <tbody>
            @foreach($alumn as $key => $value)
              <tr>
                <td>{{$value["Clave"]}}</td>
                <td>{{$value["Matricula"]}}</td>
                <td>{{$value["Alumno"]}}</td>
                <td>{{$value["Telefono"]}}</td>
                <td>{{$value["Email"]}}</td>
                <td>{{$value["Status"]}}</td>
              </tr>
            @endforeach
          </tbody>

        </table>

      </div>

    </div>

<!-- <div class="card">
  
  <div class="card-body">

    <div class="row">

      <div class="col-md-12">

        <h4>Reportes</h4> 

      </div>

      <div class="col-md-3 col-sm-12">

        <div class="small-box bg-success">

          <div class="inner">

            <h3>correos</h3>

            <p></p>

          </div>

          <div class="icon">

            <i class="fa fa-user"></i>

          </div>

          <a href="" class="small-box-footer">Cambiar<i class="fas fa-arrow-circle-right"></i></a>

        </div>
        
      </div>

    </div>

  </div>

</div> -->
<script>
  $(".tableReport").dataTable({
    "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ]
  });

  $("#print").click(function(){
    window.print();
  });

</script>
@stop