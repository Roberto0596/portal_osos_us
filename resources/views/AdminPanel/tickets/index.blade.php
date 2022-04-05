@extends('AdminPanel.main')

@section('content-admin')

<style>
    .select2-container {
        box-sizing: border-box;
        display: inline-block;
        margin: 0;
        position: relative;
        vertical-align: middle;
        width: 90% !important;
    }
</style>

<div class="content-wrapper">

    <section class="content-header">
    
        <div class="container-fluid">
          
            <div class="row mb-2">
              
                <div class="col-sm-6">
                  
                    <h1>Recibos</h1>
                  
                </div>
              
                <div class="col-sm-6">
                  
                    <ol class="breadcrumb float-sm-right">
                      
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active"><a href="#">Recibos</a></li>
                      
                    </ol>
                  
                </div>
              
            </div>
          
        </div>
        
    </section>

 
    <section class="content">
      
        <div class="card">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-12">
                      <h4>Filtros</h4>
                    </div>

                    <div class="col-md-4">
                        <label for="">Periodo</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-th-list"></i></span>
                            </div>

                            <select id="period" class="form-control">
                                @foreach(periodsById() as $key => $value)
                                   <option value="{{$value->id}}">{{$value->clave}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">

                        <label for="">Concepto</label>

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-asterisk"></i></span>
                            </div>

                            <select id="concept" class="form-control">
                                <option value="all">Todos</option>
                                @foreach(selectTable('debit_type') as $key => $value)
                                   <option value="{{$value->id}}">{{$value->concept}}</option>
                                @endforeach
                            </select>

                        </div>

                    </div>

                    <div class="col-md-4">           

                        <label for="">Alumno</label>

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-user"></i></span>
                            </div>

                            <select class="form-control" name="id_alumno" id="id_alumno" require>
                                <option value="">Seleccione un alumno</option>
                            </select>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="card">

            <div class="card-body">

                <table class="table table-bordered table-hover dt-responsive" id="tableTickets">

                    <thead>

                        <tr>
                            <th>Alumno</th>
                            <th>Concepto</th>
                            <th>Monto</th>
                            <th>Tipo de Adeudo</th>
                            <th>Periodo</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>

                    </thead>

                </table>

            </div>

        </div>

    </section>

</div>

<script>
    const op = { style: 'currency', currency: 'USD' };
    const nf = new Intl.NumberFormat('en-US', op);
    $("#id_alumno").select2({
        ajax: {
            url: "/admin/search-alumn",
            dataType: 'json',
            data: function (params) {
                return {
                    filter: params.term, // search term
                };
            },
            processResults: function (data,params) {
                return {
                    results: data.results, // search term
                };
            },
            cache: true
        },
        placeholder: 'Buscar alumno',
        minimumInputLength: 3,
        width: 'resolve',
    });

    const filters = {
        period: null,
        concept: null,
        id_alumno: null,
        init: function() {

            $("#period").change(function() {            
                filters.period = $("#period").val();
                localStorage.setItem("period", filters.period);
                Datatable.dataTable.draw();
            });

            $("#concept").change(function() {            
                filters.concept = $("#concept").val();
                localStorage.setItem("concept", filters.concept);
                Datatable.dataTable.draw();
            });

            $("#id_alumno").change(function() {
                filters.id_alumno = $("#id_alumno").val();
                localStorage.setItem("id_alumno", filters.id_alumno);
                Datatable.dataTable.draw();
            });

            var id_alumno = localStorage.getItem("id_alumno");
            var concept = localStorage.getItem("concept");
            var period = localStorage.getItem("period");

            $("#id_alumno option[value="+ id_alumno +"]").attr("selected",true);
            $("#concept option[value="+ concept +"]").attr("selected",true);
            $("#period option[value="+ period +"]").attr("selected",true);

            filters.id_alumno = $("#id_alumno").val();
            filters.concept = $("#concept").val();
            filters.period = $("#period").val();
        }
    };

    var Datatable = {
        table: $("#tableTickets"),
        init: () => {
            Datatable.dataTable = Datatable.table.DataTable({
                "destroy": true,
                "processing": true,
                "responsive": true,
                "serverSide": true,
                "stateSave": true,
                "ajax": {
                    "url": "{{ route('admin.ticket.datatable') }}",
                    "headers":{'X-CSRF-TOKEN' : "{{ csrf_token() }}"},
                    "type": "POST",
                    "data": {
                        "period": function() {
                            return filters.period;
                        },
                        "concept": function() {
                            return filters.concept;
                        },
                        "id_alumno": function() {
                            return filters.id_alumno;
                        }
                    },
                },
                "columns":[
                    {"data": "alumnName", "orderable": false},
                    {"data": "concept"},
                    {"data": "amount", "render": (data) => {
                        return nf.format(data);
                    }},
                    {"data": "debit_type"},
                    {"data": "clave"},
                    {"data": "created_at"},
                    {"data": null, "orderable": null, "render": (data) => {
                        return "<div class='btn-group'><button route='"+data.route+"' title='Ver comprobante' class='btn btn-primary btnPrint'><i class='fa fa-eye'></i></button></div>"
                    }}
                ],
                "language": datatableSpanish
            });
        }
    };

    $(document).on("click",'button.btnPrint', function(){
        let route = $(this).attr("route");
        window.open(`/${route}`, '_blank');
    });

    filters.init();
    Datatable.init();
</script>
 
@stop