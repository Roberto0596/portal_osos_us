@extends('layout')

@section('content')

<div class="imagen-fondo">
   
    @include('Website.header')

    <div class="bienvenida">

        <h2>Bienvenido oso, tenemos estas noticias para ti!</h2>

        <div class="feed2">

            <div class="feed_content">

                <div class="feed-header">
                    <h1>encabezado2</h1>
                </div>

                <div class="feed-body">
                    <p>Parrafo</p>
                </div>

            </div>
                        
        </div>
    
    </div>


    <div class="login-form">

        <form action="" method="post">

            {{ csrf_field() }}

            <h2 class="text-center">Iniciar sesión</h2>  

            <div class="input-group mb-3">

                <label class="field a-field a-field_a2">

                    <input class="field__input a-field__input" placeholder="example@example.com" id="email" name="email" required>

                    <span class="a-field__label-wrap">

                        <span class="a-field__label">Correo</span>

                    </span>

                </label> 

            </div>

            <div class="input-group mb-3">

                <label class="field a-field a-field_a2">

                    <input type="password" class="field__input a-field__input" placeholder="Ingresa tu contraseña" id="password" name="password" required>

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

    <div>hola</div>

</div>

@stop




<!--<div class="bienvenida">
    <h2>
        Bienvenido oso, tenemos estas noticias para ti!
    </h2>
</div>
<div class="login-form">

    <form action="" method="post">

         {{ csrf_field() }}

        <h2 class="text-center">Iniciar sesión</h2>  

        <div class="form-group">

        <input type="text" class="form-control" placeholder="Correo" required="required" name="email">

         </div>

        <div class="form-group">

            <input type="password" class="form-control" placeholder="Contraseña" required="required" name="password">

        </div>

        <div class="form-group">

            <button type="submit" class="btn btn-primary btn-block boton">Entrar</button>

        </div>
    </form> 
</div>

-->
