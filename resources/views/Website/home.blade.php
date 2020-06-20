@extends('Website.main')

@section('main-content')

<div id="hero">
                
    <div class="contenedor text-center">
        
        <div class="title">

            <h1>Universidad de la Sierra</h1>

            <span>!Bienvenido!</span>

        </div>     

    </div> 

</div>

<div class="back">
	
	<div class="container">

		<div class="row">

			<div class="col-md-4 col-sm-12" style="margin-bottom: 55px;">

                <div class="container-custom">

                    <a href="">

                        <div class="card2">

                            <img src="{{asset('img/temple/avatar.jpg')}}" alt="" class="card-image-rob">
                            <h4 class="titulo-cards">¿Eres alumno?</h4>
                            <p class="parrafo">Ya puedes inscribirte</p>

                        </div>
                    </a>

                </div>

            </div>

            <div class="col-md-4 col-sm-12" style="margin-bottom: 55px;">

                <div class="container-custom">

                    <a href="">

                        <div class="card2">

                            <img src="{{asset('img/temple/avatar.jpg')}}" alt="" class="card-image-rob">
                            <h4 class="titulo-cards">¿Quieres ser alumno unisierra?</h4>
                            <p class="parrafo">Ya puedes hacer tu pre inscripcion</p>

                        </div>
                    </a>

                </div>

            </div>

		</div>

	</div>

</div>

@stop