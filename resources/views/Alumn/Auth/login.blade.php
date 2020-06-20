@extends('layout')

@section('content')

<link rel="stylesheet" href="{{asset("css/app.css")}}">



    







<body class="imagen-fondo">
<nav class="navbar navbar-expand-lg navbar-light navbar-orange">
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	    <span class="navbar-toggler-icon"></span>
	  </button>

	  <div class="collapse navbar-collapse" id="navbarSupportedContent">
	    <ul class="navbar-nav mr-auto">
	      <li class="nav-item active">
	        <a class="nav-link" href="{{route('home')}}">Inicio</a>
	      </li>
	      <li class="nav-item active">
	        <a class="nav-link" href="{{route('home')}}">Plan de estudios</a>
	      </li>
	    </ul>
	  </div>
	</nav>

<div class="bienvenida">
    <h2>
        Bienvenido oso, tenemos estas noticias para ti!
    </h2>
</div>
    <div class="login-form">

        <form action="" method="post">

            <h2 class="text-center">Iniciar sesión</h2>  

            <div class="form-group">

            <input type="text" class="form-control" placeholder="Correo" required="required">

             </div>

            <div class="form-group">

                <input type="password" class="form-control" placeholder="Contraseña" required="required">

            </div>

            <div class="form-group">

                <button type="submit" class="btn btn-primary btn-block boton">Entrar</button>

            </div>
        </form> 
    </div>

    <div class="feed">

		<div class="feed_content">

			<div class="feed-header">
				<h1>encabezado2</h1>
			</div>

			<div class="feed-body">
				<p>Parrafo</p>
			</div>

		</div>
						
	</div>
</body>








@stop