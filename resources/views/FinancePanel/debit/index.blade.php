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

  .custom-modal{
      text-align: center;
  }

  .loader {
  border: 10px solid #f3f3f3; 
  border-top: 10px solid #28a745; 
  border-radius: 50%;
  width: 80px;
  height: 80px;
  animation: spin 2s linear infinite;
  margin-left: 40%
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
 
 
</style>

<div class="content-wrapper">

  <section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1>Procesar pagos</h1>

        </div>

        <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right">

            <button data-toggle="modal" data-target="#modalDebit" class="btn btn-warning button-custom"><i class="fa fa-fw fa-plus"></i>Nuevo adeudo</button>

          </ol>

        </div>

      </div>

    </div>

  </section>

  <section class="content">

    <div class="card">

      <div class="card-body">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">

        <table class="table table-bordered table-hover tableDebits">

          <thead>

            <tr>
              <th style="width: 10px">#</th>
              <th>Concepto</th>
              <th>Monto</th>
              <th>Encargado</th>
              <th>Alumno</th>
              <th>Estado</th>
              <th>Fecha</th>
              <th>Acciones</th>
            </tr>

          </thead>

        </table>

      </div>

    </div>

  </section>

</div>
 <!-- Modal crear nuevo adeudo -->

<div class="modal fade" id="modalDebit">

  <div class="modal-dialog modal-lg">

    <div class="modal-content">

        <form method="post" action="{{route('finance.debit.save')}}">
            
            {{ csrf_field() }}

            <div class="modal-header">

                <h4 class="modal-title">GENERAR NUEVO ADEUDO</h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
        
            <div class="modal-body">

                <div class="row">

                    <div class="col-md-6">           

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-credit-card"></i></span>
                            </div>

                            <input type="text" name="concept" id="concept" placeholder="¿Cual es el concepto?" class="form-control form-control-lg" required>

                        </div>

                    </div>

                    <div class="col-md-6">           

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-credit-card"></i></span>
                            </div>

                            <input type="number" step="any" min="0" name="amount" id="amount" placeholder="¿Cual es el monto?" class="form-control form-control-lg" required>

                        </div>

                    </div>

                    <div class="col-md-6">           

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-credit-card"></i></span>
                            </div>

                            <select class="form-control form-control-lg" id="id_alumno" name="id_alumno" style="width:88%" require>
                                <option value="">Seleccione un alumno</option>
                                @php
                                    $alumnos = selectSicoes("Alumno");
                                @endphp

                                @foreach($alumnos as $key => $value)
                                <option value="{{$value['AlumnoId']}}">{{$value["Matricula"]." ".$value["Nombre"]}}</option>
                                @endforeach

                            </select>

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


 <!-- Modal Editar-->
 <div class="modal fade" id="modalEdit">

  <div class="modal-dialog modal-lg">

    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="tokenModal">

        <form method="post" action="{{route('finance.debit.update')}}">
            @method('PUT')
            
            {{ csrf_field() }}
            <input type="hidden" id="DebitIdUpdate" name="DebitId">
           

            <div class="modal-header">

                <h4 class="modal-title">EDTAR ADEUDO</h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
        
            <div class="modal-body">

                <div class="row">

                    <div class="col-md-6">           

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-credit-card"></i></span>
                            </div>

                            <input type="text" name="concept" id="EditConcept" placeholder="¿Cual es el concepto?" class="form-control form-control-lg" required>

                        </div>

                    </div>

                    <div class="col-md-6">           

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-credit-card"></i></span>
                            </div>

                            <input type="number" step="any" min="0" name="amount" id="EditAmount" placeholder="¿Cual es el monto?" class="form-control form-control-lg" required>

                        </div>

                    </div>

                    <div class="col-md-6">           

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-credit-card"></i></span>
                            </div>

                            <select class="form-control form-control-lg" id="EditId_alumno" name="id_alumno" style="width:88%" require>
                                <option value="">Seleccione un alumno</option>
                                @php
                                    $alumnos = selectSicoes("Alumno");
                                @endphp

                                @foreach($alumnos as $key => $value)
                                <option value="{{$value['AlumnoId']}}">{{$value["Matricula"]." ".$value["Nombre"]}}</option>
                                @endforeach

                            </select>

                        </div>

                    </div>

                    <div class="col-md-6">           

                      <div class="input-group mb-3">

                          <div class="input-group-prepend">
                              <span class="input-group-text">
                              <i class="fas fa-credit-card"></i></span>
                          </div>

                          <select class="form-control form-control-lg" id="EditStatus" name="status" style="width:88%" require>
                              <option value="">Cambiar estado de pago</option>
                              <option value="0">Pendiente</option>
                              <option value="1">Pagado</option>
                          </select>

                      </div>

                  </div>

                </div>

            </div>

            <div class="modal-footer justify-content">

                <div class="col-sm container-fluid">

                    <div class="row">

                        <div class=" col-sm-6 btn-group">

                        <button id="cancelEdit" type="button" class="btn btn-danger .px-2 " data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>

                        </div>

                        <div class=" col-sm-6 btn-group">

                        <button type="submit" id="saleEdit" class="btn btn-success .px-2"><i class="fa fa-check"></i> Guardar</button>
                        
                        </div>

                    </div>

                </div>

            </div>

       </form>

    </div>

  </div>

</div>

<!-- TerminaModal -->

 <!-- Modal  de detalles de pago -->
 <div class="modal fade" id="modalShowDetails">

    <div class="modal-dialog modal-lg">
  
      <div class="modal-content">
          <input type="hidden" name="_token" value="{{ csrf_token() }}" id="tokenModal">
  
         
              
              
              {{ csrf_field() }}
              <input type="hidden" id="DebitIdUpdate" name="DebitId">
             
  
              <div class="modal-header">
  
                  <h4 class="modal-title">DETALLES DEL PAGO</h4>
  
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
  
              </div>
          
              <div class="modal-body custom-modal">
                <div id="loader" class="loader"></div>
                <h6 id="detail-id"></h6>
                <h6 id="detail-paymentMethod"></h6>
                <h6 id="detail-reference"></h6>
                <h6 id="detail-amount"></h6>
                <h6 id="detail-order"></h6>
  
              </div>
  
              <div class="modal-footer justify-content">
  
                  <div class="col-sm container-fluid">
  
                      <div class="row" style="margin-left: 32%">
  
                          <div class=" col-sm-6 btn-group">
  
                          <button id="cancelEdit" type="button" class="btn btn-danger .px-2 " 
                          data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
  
                          </div>
  
                      </div>
  
                  </div>
  
              </div>
  
         
  
      </div>
  
    </div>
  
  </div>
 
<script>

var token = $("#token").val();
var route = "/finance/debit/show";

$(".tableDebits").dataTable({
    "destroy": true,
    "ajax":
    {
        url: route,
        headers:{'X-CSRF-TOKEN':token},
        type: "PUT",
    },
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

$("#id_alumno").select2({
    width: 'resolve'
});




$(".tableDebits tbody").on("click","button.edit",function()
{
    var DebitId = $(this).attr("DebitId");
    var route = '/finance/debit/see';
    var token = $('#tokenModal').val();
    var data = new FormData();
    data.append('DebitId', DebitId);
    $.ajax({
        url:route,
        headers:{'X-CSRF-TOKEN': token},
        method:'POST',
        data:data,
        cache:false,
        contentType:false,
        processData:false,
        success:function(response)
        {
          
            $('#EditConcept').val(response['concept']);
            $('#EditAmount').val(response['amount']);
            $('#EditId_alumno').val(response['alumnId']);
            $('#EditStatus').val(response['status']);
            $('#DebitIdUpdate').val(response['debitId']);
            
           
        }});
});


$(".tableDebits tbody").on("click","button.details",function()
{

  
    var DebitId = $(this).attr("DebitId");
    var route = '/finance/debit/payment-details';
    var token = $('#tokenModal').val();
    var data = new FormData();
    data.append('DebitId', DebitId);
    $.ajax({
        url:route,
        headers:{'X-CSRF-TOKEN': token},
        method:'POST',
        data:data,
        cache:false,
        contentType:false,
        processData:false,
        success:function(response)
        {
           
            
            $('#loader').hide();
            $('#detail-id').text("ID: " +response['id']);
            $('#detail-paymentMethod').text("Método de pago: " +response['paymentMethod']);
            $('#detail-reference').text("Referencia: " +response['reference']);
            $('#detail-amount').text("Monto: " +response['amount']);
            $('#detail-order').text("Orden: " +response['order']);

        
           
        }});
});






</script>



@stop