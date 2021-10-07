@extends('DepartamentPanel.main')

@section('content-departament')

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<div class="content-wrapper">

  <section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1>Reportes</h1>

        </div>

        <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right">

            <li class="breadcrumb-item active"><a href="#">Home</a></li>

          </ol>

        </div>

      </div>

    </div>

  </section>

  <section class="content">

    <div class="card">
      
      <div class="card-body">

        <div class="row" style="margin-bottom: 1rem">

          <div class="col-md-4">

            <label for="">Rango de fechas</label>

            <div class="input-group mb-3">

              <div class="input-group-prepend">

                <span class="input-group-text"><i class="fas fa-calendar"></i></span>

              </div>

              <input type="text" class="form-control" id="datepicker-report" placeholder="Rango de fechas">

            </div>

          </div>

        </div>

        <table class="table table-bordered table-hover dt-responsive">

          <thead>
            <tr>
              <!-- <th style="width: 10px">#</th> -->
              <th>Matricula</th>
              <th>Nombre</th>
              <th>Equipo</th>
              <th>Sala</th>
              <th>Hora entrada</th>
              <th>Hola salida</th>
              <th>Fecha</th>
            </tr>  
          </thead>

        </table>

      </div>

    </div>

  </section>

</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>

  var filters = {
    initDate: null,
    endDate: null,
    init: () => {
      $('#datepicker-report').daterangepicker({
        autoUpdateInput: false,
        locale: {
           cancelLabel: 'Clear'
        },
        ranges: {
         'Hoy': [moment(), moment()],
         'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
         'Ultimos 7 días': [moment().subtract(6, 'days'), moment()],
         'Ultimos 30 días': [moment().subtract(29, 'days'), moment()],
         'Este mes': [moment().startOf('month'), moment().endOf('month')],
         'Ultimo mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
      }, function(start, end, label) {
        $("#datepicker-report").val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
        filters.initDate = start.format('YYYY-MM-DD');
        filters.endDate = end.format('YYYY-MM-DD');
        Datatable.dataTable.draw();
      });
    }
  };

  var Datatable = {
    table: $(".table"),
    init: () => {
      Datatable.dataTable = Datatable.table.DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
          "url": "{{ route('departament.logs.report.datatable') }}",
          "type": "POST",
          "headers":{'X-CSRF-TOKEN' : '{{ csrf_token() }}'},
          "data": {
            "initDate": () => {
              return filters.initDate;
            }, 
            "endDate": () => {
              return filters.endDate;
            }
          }
        },
        "columns": [
          {"data": "enrollment", "render": (data) => {
            return "lol";
          }},
          {"data": "full_name"},
          {"data": "equipment"},
          {"data": "classroom"},
          {"data": "entry_time"},
          {"data": "departure_time"},
          {"data": "Date"}
        ],
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
    }
  }
  
  filters.init();
  Datatable.init();

</script>

@stop
