@extends('FinancePanel.main')

@section('content-finance')

<style>
  .page-item.active .page-link {
    background-color: #fd7e14;
    border-color: #fd7e14;
  }
  .textAndButton{
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    align-items: center;
  }

  
 
  .custom{
    background-color: #fd7e14;
    border-color: #fd7e14;
    color: white;
  }

  .custom:hover{
    background-color: #e96c06;
    border-color: #e96c06;
    color: white;
  }
  .modal-header{
    background-color: #28a745;
    color: white;
  }
 
 
</style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Blank Page</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Blank Page</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">

        <div class="card-body">

          <table class="table table-bordered table-hover tableDebit" id="tableDebits">

            <thead>

              <tr>
                <th>ALUMNO</th>
                <th>CONCEPTO</th>
                <th>MONTO</th>
                <th>MÉTODO DE PAGO</th>
                <th>ESTADO</th>
                <th>Fecha</th>
                <th>ACCIONES</th>
              </tr>

            </thead>

          </table>
          
        </div>
       
      </div>
      <!-- /.card -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

   <!-- Modal Editar el estado de pago -->
   <div class="modal fade" id="changeStatusDebit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">CAMBIAR EL ESTADO DEL PAGO</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h6>CONCEPTO: </h5>
          <h6>ALUMNO: </h5>
          <h6>MONTO: </h5>
          <br>
          <form action="{{ route('finance.changePaymentStatus')}}" method="POST">
            {{ csrf_field() }}

            <div class="form-group">
              <label for="status"  class="control-label">ESTADO ACTUAL DEL PAGO</label>
              <select  name="status" class="form-control "  >
                  <option   value="1">PAGADO</option>
                  <option  value="0"> PENDIENTE </option>
                 
              </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-warning custom" data-dismiss="modal">CERRAR</button>
            <button type="submit" id="btnSave"  class="btn btn-success">GUARDAR</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- TerminaModal -->

   <!-- Modal  de pago -->
   <div class="modal fade" id="showPayModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">INFORMACIÓN DEL PAGO</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h6>CONCEPTO:</h5>
          <h6>ALUMNO: </h5>
          <h6>"MONTO: </h5>
          <br>
          <form action="#" method="POST">
            @csrf 
            <div class="form-group">
              <label for="status"  class="control-label">ESTADO ACTUAL DEL PAGO</label>
              <select  name="status" class="form-control "  >
                  <option   value="1">PAGADO</option>
                  <option  value="0"> PENDIENTE </option>
                 
              </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-warning custom" data-dismiss="modal">CERRAR</button>
            <button type="submit" id="btnSave"  class="btn btn-success">GUARDAR</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- TerminaModal -->

  <script>

$(document).ready(function() {
  $('#tableDebits').css('min-height','300px');


  $(".tableDebit").dataTable({
    "language": {

        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }

      }
  });

});

    
  </script>

@stop
