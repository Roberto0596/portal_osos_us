<header>

	<nav class="navbar navbar-expand-lg navbar-light navbar-orange">
	    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	    	<span class="navbar-toggler-icon"></span>
	    </button>

	  	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		    <ul class="navbar-nav mr-auto">
		      <li class="nav-item active">
		        <a class="nav-link link-custom header-link" href="{{route('home')}}">Inicio</a>
		      </li>
		      <li class="nav-item active">
		        <a class="nav-link link-custom header-link" href="http://www.unisierra.edu.mx/oferta_educativa/oferta-educativa.htm" target="_blank">Plan de estudios</a>
		      </li>
		    </ul>
	  	</div>

	  	{{-- @if(Route::currentRouteName()=='home')

	  		<span class="header-link" style="margin-right: 1%">Estoy Registrado/Ya soy Alumno -> </span> 
			<a href="{{route('alumn.home')}}" class="btn btn-success my-2 my-sm-0" style="margin-right: 1%; color: white; border-radius: 20px;"> Alumnos</a>
			<a href="{{route('alumn.home')}}" class="btn btn-warning button-custom my-2 my-sm-0" style="color: white; border-radius: 20px;"> Aspirantes</a>

	  	@endif --}}
		  

	</nav>

</header>

