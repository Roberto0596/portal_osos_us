@extends('layout')

@section('content')

<link rel="stylesheet" href="{{ asset('css/computer_log.css') }}">

<div class="content-custom">

    <div class="back2">

        <div class="row">

            <div class="col-lg-12 col-md-12">

                <div class="login-form">

                    <form action="{{ route('logs.classroom.index') }}" method="post" id="form-login">

                        {{ csrf_field() }}

                        <h2 class="text-center">Bienvenido al centro de computo</h2>

                        <br>
                        <br>  

                        <div class="input-group mb-3">

                            <label class="field a-field a-field_a2">

                                <input class="field__input a-field__input" id="enrollment" name="enrollment" placeholder="00-00-0000" required>

                                <span class="a-field__label-wrap">

                                    <span class="a-field__label">Matricula</span>

                                </span>

                            </label> 

                        </div>

                        <div class="form-group">

                            <button type="submit" class="btn btn-primary btn-block boton" style="border-radius: 19px;">Entrar</button>

                        </div>

                    </form> 

                </div> 

            </div>        

        </div>   

    </div>

</div>

<div class="modal fade" id="quick-booking" data-backdrop='static' data-keyboard=false>

  <div class="modal-dialog modal-lg">

    <div class="modal-content">

      <div class="modal-header text-center">

        <h3 class="center">Reserva rapida</h3>

      </div>
        
      <div class="modal-body">

        <div class="row">

            <div class="container">
                
                <div class="row">
                    
                    <div class="col-md-12">
                        
                        <div class="w-100 flex-content">

                            <div class="flex-content circle-orange"><span id="num"></span></div>
                            
                        </div>

                    </div>

                    <div class="col-md-12">
                        <div class="modal-description">
                            <p>Tenemos disponible esta computadora, tu decides.</p>
                        </div>
                    </div>

                </div>

            </div>

        </div>
            
      </div>

      <div class="modal-footer">
          <div class="row">
              <div class="col-md-4 ">
                  <button class="btn btn-default w-100" id="close-modal">Cancelar</button>
              </div>
              <div class="col-md-4">
                  <button class="btn btn-primary w-100" id="decline">Elijo yo</button>
              </div>
              <div class="col-md-4">
                  <button class="btn btn-success float-right w-100" id="accept">Tomaré esa</button>
              </div>
          </div>
      </div>

    </div>

  </div>

</div>

<script>

    var onFocus = true;
    var quick_equipment;

    $(document).click(function() {
        if (onFocus) {
            $("#enrollment").focus();
        }
    });

    $(document).ready(function() {
        $("#enrollment").focus();
        $('#enrollment').mask('00-00-0000');
    });

    $("#close-modal").click(function() {
        $("#quick-booking").modal('hide');
        onFocus=true;
    });

    $("#form-login").submit(function(e) {
        var $form = $("#form-login");
        var enrollment = $("#enrollment").val();
        e.preventDefault();

        $.get("{{ route('logs.quick.get') }}?enrollment="+enrollment, function(data) {

            if (data.thereIsRecord) {

                swal.fire({
                    title: '¿Quieres cerrar tu reservación?',
                    text: "¡Al hacerlo la maquina será bloqueada!",
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Si, cerrar reservación'
                }).then((result)=>
                {
                    if (result.value)
                    {
                         closeReservacion(data.id_temp);
                    }
                });

            } else {

                if (data.missingRegister) {
                    swal.fire({
                        title: "Alumno no encontrado",
                        text: "Revisa tu Matricula y vuelve a intentarlo",
                        type: 'warning',
                        buttons: "Aceptar"
                    }).then((value) => {
                        $("#enrollment").focus();
                        $("#enrollment").val(""); 
                    });                
                } else {

                    if (data.status == "failed") {
                        $form.get(0).submit();
                    }

                    quick_equipment = data.id_equipment; 
                    $("#num").text(data.num);
                    $("#quick-booking").modal("show");
                    onFocus = false;
                }
            }
        });
    });

    $("#decline").click(function() {
        var $form = $("#form-login");
        $form.get(0).submit();
    });

    $("#accept").click(function() {
        var enrollment = $("#enrollment").val();
        var data = new FormData();
        data.append("enrollment", enrollment);
        data.append("id_equipment", quick_equipment);
        $.ajax({
            url:"{{ route('logs.quick.save') }}",
            headers:{'X-CSRF-TOKEN': "{{ csrf_token() }}"},
            method:'POST',
            data:data,
            cache:false,
            contentType:false,
            processData:false,
            success:function(response) {      
                swal.fire({
                    title: response.message,
                    text: "",
                    type: response.status,
                    buttons: "Aceptar"
                }).then((value) => {
                    $("#enrollment").focus();
                    $("#enrollment").val("");
                    $("#close-modal").click();
                    quick_equipment = null;
                }); 
            }
        });
    });

    function closeReservacion(id_temp) {
        var data = new FormData();
        data.append("id_temp", id_temp);
        $.ajax({
            url:"{{ route('logs.close.booking') }}",
            headers:{'X-CSRF-TOKEN': "{{ csrf_token() }}"},
            method:'POST',
            data:data,
            cache:false,
            contentType:false,
            processData:false,
            success:function(response) {      
                $("#enrollment").focus();
                $("#enrollment").val("");
                quick_equipment = null;
                onFocus = true;
            }
        });
    }

</script>

@stop
