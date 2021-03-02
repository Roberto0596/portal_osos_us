@extends('Website.main')

@section('main-content')

<div class="back2">

    <div class="flex-content">

        <div class="card box-enrollement">

            @if($step==1) 

            <div class="card-header text-center">

                <h3>¿ya eres alumno?</h3>

            </div>

            @endif

            <form method="post" action="{{route('alumn.users.postStep',$step)}}" style="width: 90%; margin-right: auto; margin-left: auto; height: 100%;" id="form-account">

                {{ csrf_field() }}

                <div class="card-body" style="height: 90%;">

                    <div class="row">

                        <div class="col-md-12">

                            @if($step==1) 

                            <div class="input-group mb-3" style="">

                                <label class="field a-field a-field_a2" style="font-size: 20px;">

                                    <input type="text" class="field__input a-field__input" placeholder=" 00-00-0000" id="matricula" name="matricula" required style="font-size: 25px;">

                                    <span class="a-field__label-wrap">

                                        <span class="a-field__label">Ingresa tu matricula</span>

                                    </span>

                                </label> 

                            </div>

                            <div class="input-group mb-3" style="">

                                <label class="field a-field a-field_a2" style="font-size: 20px;">

                                    <input type="password" class="field__input a-field__input" placeholder="Ingresa tu password" id="password" name="password" required style="font-size: 25px;">

                                    <span class="a-field__label-wrap">

                                        <span class="a-field__label">Ingresa tu contraseña</span>

                                    </span>

                                </label> 

                            </div>

                            @endif

                            @if($step==2)

                            @php
                                $email = str_replace("-","",$alumn->Matricula);
                            @endphp

                            <div class="row" style="margin-top: 2vh">

                                <div class="col-md-12 text-center step_one">

                                    <p class="parraf-custom">Hola <span>{{ucwords(strtolower($alumn->Nombre))}}</span></p>

                                    <p class="parraf-custom">Tu correo sera: <span>
                                    a{{ $email }}@unisierra.edu.mx</span></p>

                                    <p class="parraf-custom">¡Apuntalo!</p>

                                </div>

                                <div class="col-md-12 text-center step_two" style="display: none">

                                    <p class="parraf-custom">{{ucwords(strtolower($alumn->Nombre))}} solo falta tu contraseña</span></p>

                                    <div class="input-group mb-3" style="">

                                        <label class="field a-field a-field_a2">

                                            <input type="password" class="field__input a-field__input" placeholder="password" id="first" required>

                                            <span class="a-field__label-wrap">

                                                <span class="a-field__label">Ingresa aquí tu contraseña</span>

                                            </span>

                                        </label> 

                                        <label class="field a-field a-field_a2">

                                            <input type="password" class="field__input a-field__input" placeholder="password" id="password" name="password" required>

                                            <input type="hidden" value="a{{$email}}@unisierra.edu.mx" name="email">

                                            <input type="hidden" value="{{ $alumn->Matricula }}" name="matricula">

                                            <span class="a-field__label-wrap">

                                                <span class="a-field__label">Confirma tu contraseña</span>

                                            </span>

                                        </label> 

                                        <div id="validate" style="width: 100%"></div>

                                    </div>

                                </div>

                            </div>
                            @endif

                        </div>

                    </div>

                </div>
                    
                <div class="row footer-custom">

                    <div class="col-md-12 col-custom">

                        @if($step==1)
                         <button type="submit" class="btn btn-warning button-custom">Siguiente <i class="fa fas  fa-arrow-circle-right"></i></button>
                        @else
                        <button type="button" class="btn btn-warning button-custom sent next">Siguiente <i class="fa fas  fa-arrow-circle-right"></i></button>
                        @endif

                    </div>

                </div>

            </form>


        </div>

    </div>

</div>

<script>
    $(document).ready(function()
    {
        @if(isset($error))
            toastr.warning("{{$error}}");
        @endif

        @if($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error('{{$error}}');
            @endforeach
        @endif
    })
</script>

<script src="{{asset('js/alumn/account.js')}}"></script>

@stop