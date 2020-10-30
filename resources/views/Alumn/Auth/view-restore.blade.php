@extends('layout')

@section('content')

<div class="content-custom">

    @include('Website.header')

    <div class="back2">

        <div class="row" style="margin: 1%; width: 100%">

            <div class="col-lg-12 col-md-12">

                <div class="login-form">

                    <form action="{{route('restore.password', $instance)}}" method="post">

                        {{ csrf_field() }}

                        <h2 class="text-center">Restauracion de contraseña</h2>  

                        <div class="input-group mb-3" style="">

                            <label class="field a-field a-field_a2">

                                <input type="password" class="field__input a-field__input" placeholder="password" id="first" required>

                                <span class="a-field__label-wrap">

                                    <span class="a-field__label">Ingresa aquí tu contraseña</span>

                                </span>

                            </label> 

                            <label class="field a-field a-field_a2">

                                <input type="password" class="field__input a-field__input" placeholder="password" id="password" name="password" required>

                                <span class="a-field__label-wrap">

                                    <span class="a-field__label">Confirma tu contraseña</span>

                                </span>

                            </label> 

                            <div id="validate" style="width: 100%"></div>

                        </div>                       

                        <div class="form-group">

                            <button id="send" type="submit" class="btn btn-primary btn-block boton sent" style="border-radius: 19px;">Guardar</button>

                        </div>                       

                    </form> 

                </div> 

            </div>        

        </div>   

    </div>

</div>
<script src="{{asset('js/alumn/account.js')}}"></script>
@stop



