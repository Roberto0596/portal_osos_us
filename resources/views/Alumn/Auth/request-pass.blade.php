@extends('layout')

@section('content')

<div class="content-custom">

    @include('Website.header')

    <div class="back2">

        <div class="row" style="margin: 1%; width: 100%">

            <div class="col-lg-12 col-md-12">

                <div class="login-form">

                    <form action="{{route('alumn.sendRequest')}}" method="post">

                        {{ csrf_field() }}

                        <h3 class="text-center">Ingresa tu correo</h3>  

                        <div class="input-group mb-3">

                            <label class="field a-field a-field_a2">

                                <input class="field__input a-field__input" placeholder="example@example.com" id="email" name="email" required>

                                <span class="a-field__label-wrap">

                                    <span class="a-field__label">Correo</span>

                                </span>

                            </label> 

                        </div>                       

                        <div class="container">

                            <button id="send" type="submit" class="btn btn-primary btn-block boton">Solicitar</button>

                            <a class="btn boton-danger btn-block" href="{{ route('alumn.login') }}">Regresar</a>

                        </div>                       

                    </form> 

                </div> 

            </div>        

        </div>   

    </div>

</div>

@stop



