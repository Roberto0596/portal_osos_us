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
        <div class="card-header">
          <h3 class="card-title">Title</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <table class="table table-bordered table-hover tableDebit" id="tableDebits">
            <thead>
              <tr>
                <th style="width: 10px">#</th>
                <th>Alumno</th>
                <th>Concepto</th>
                <th>Monto</th>
                <th>MÉTODO DE PAGO</th>
                <th>ESTADO</th>
                <th>ACCIONES</th>
              </tr>
  
            </thead>
  
            <tbody>

              @foreach ($debits as $debit)
              <tr>
                <td>{{$debit->id}}</td>
                <td>{{$debit->name." ".$debit->lastname}}</td>
                <td>{{ strtoupper($debit->concept)   }}</td>
                <td>{{$debit->amount."\$"}}</td>
                @php
                if($debit->payment_method == 'card'){
                  $paymentMethod = 'TRAJETA';
                }elseif ($debit->payment_method == 'oxxo_cash') {
                  $paymentMethod = 'EFECTIVO EN OXXO';
                }elseif ($debit->payment_method == 'spei') {
                  $paymentMethod = 'PAGO POR SPEI';
                }elseif ($debit->payment_method == 'transfer') {
                  $paymentMethod = 'TRANSFERENCIA';;
                }
               
                @endphp
                <td>{{$paymentMethod}}</td>
                <td>{{$debit->status == '0' ? 'PENDIENTE' : 'PAGADO' }}
                  
                   <!-- Modal -->
                  <div class="modal fade" id="changeStatusDebit{{$debit->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">CAMBIAR EL ESTADO DEL PAGO</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <h6>{{"CONCEPTO: " . strtoupper($debit->concept)}}</h5>
                          <h6>{{"ALUMNO: " . $debit->name." ".$debit->lastname}}</h5>
                          <h6>{{"MONTO: " .$debit->amount."\$"}}</h5>
                          <br>
                          <form action="{{ route('finance.changePaymentStatus', $debit->id)}}" method="POST">
                            @csrf @method('PUT')
                            <div class="form-group">
                              <label for6"status"  class="control-label">ESTADO ACTUAL DEL PAGO</label>
                              <select  name="status" class="form-control "  >
                                  @if($debit->status == '0')                    
                                  <option selected value="{{$debit->status}}"> PENDIENTE </option>
                                  <option   value="1">PAGADO</option>
                                  @else
                                  <option  selected value="{{$debit->status}}">PAGADO</option>
                                  <option  value="0"> PENDIENTE </option>
                                  @endif
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

                </td>
                <td>
                  <div>
                    @if ($debit->payment_method == 'card')
                    <object  type="application/x-pdf" title="{{strtoupper($debit->concept)}}" width="5" height="5">
                      <a href="{{$debit->id_order}}" class="btn btn-warning custom"><i class="fa fa-file" title="Comprobante"></i></a>
                    </object>
                    @else
                    <object  type="application/x-pdf" title="{{strtoupper($debit->concept)}}" width="5" height="5">
                      <a href="{{route('finance.showTicket',[  $debit->id_order])}}" class="btn btn-warning custom">
                      <i class="fa fa-file" title="Comprobante"></i></a>
                    </object>
                    <button  class="btn btn-success" data-toggle="modal" title="Cambair estado de pago"
                    data-target="#changeStatusDebit{{$debit->id }}"> <i class="fa fa-pen"></i></button>
                        
                    @endif
                   
                  </div>
                </td>
               
              </tr>
              @endforeach
            </tbody>
          </table>
          
          
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          Footer
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <script>

$(document).ready(function() {
  $('#tableDebits').css('min-height','300px');


  $(".tableDebit").dataTable({
      "language": {
        "lengthMenu": "MOSTRANDO _MENU_ REGISTROS POR PÁGINA",
        "zeroRecords": "NO HAY REGISTROS",
        "info": "MOSTRANDO PÁGINA _PAGE_ DE _PAGES_",
        "infoEmpty": "NO HAY REGISTROS DISPONIBLES",
        "infoFiltered": "(FILTRADO POR _MAX_ REGISTROS)",
        "paginate": {
          "previous": "ANTERIOR",
          "next": "SIGUIENTE"
        },
        search: "_INPUT_",
        searchPlaceholder: "BUSCAR"
      }
  } );

} );

    
  </script>

@stop
