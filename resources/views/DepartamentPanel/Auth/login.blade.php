@extends('layout')

@section('content')

<div class="content-custom">

    @include('Website.header')

    <div class="back2">

        <div class="row" style="margin: 1%; width: 100%">

            <div class="col-lg-12 col-md-12">

                <div class="login-form">

                    <form action="" method="post">

                        {{ csrf_field() }}

                        <h2 class="text-center">Iniciar sesión</h2>  

                        <div class="input-group mb-3">

                            <label class="field a-field a-field_a2">

                                <input class="field__input a-field__input" placeholder="example@example.com" id="email" name="email" required  value="{{ old('email') }}">

                                <span class="a-field__label-wrap">

                                    <span class="a-field__label">Correo</span>

                                </span>

                            </label> 

                        </div>

                        <div class="input-group mb-3">

                            <label class="field a-field a-field_a2">

                                <input type="password" class="field__input a-field__input" placeholder="Ingresa tu contraseña" name="password" required>

                                <span class="a-field__label-wrap">

                                    <span class="a-field__label">Contraseña</span>

                                </span>

                            </label> 

                        </div>

                        <div class="form-group">

                            <button type="submit" class="btn btn-primary btn-block boton">Entrar</button>

                        </div>
                    </form> 
                </div> 

            </div>        

        </div>   

    </div>

</div>

<script src="{{asset('js/website/home.js')}}"></script>

@stop
