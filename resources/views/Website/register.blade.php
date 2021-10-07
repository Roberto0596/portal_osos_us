@extends('Website.main')

@section('main-content')


<style>
	.btns-row{
		display: flex;
		flex-direction: row;
		justify-content: space-around;
		margin-top: 2rem;
		align-items: center;
	}

	@media (min-height: 768px) { 

		.btns-row{
			margin-top: 10rem;
		}
		
	}
</style>

<div class="back2">

	<div class="row" style="margin: 1%">

		<div class="col-md-6">

			<div class="row">

				<div class="col-md-12 osos-title">
					<h1 class="feel-title">Se un Oso Unisierra</h1>
				</div>

				<div class="col-md-11">

					<div class="feed">

						<div class="feed_content">

							<div class="feed-header">
								<h3>#Inscripciones.</h3>
							</div>

							<div class="feed-body">
								<p>El módulo de inscripciones ya está disponible. Primero registrate en el portal llenando los datos en el panel derecho; Segundo, accede con tus datos y llena el formulario.</p>
							</div>

						</div>
						
					</div>

					<div class="feed">

						<div class="feed_content">

							<div class="feed-header">
								<h1>#Re-Inscripciones.</h1>
							</div>

							<div class="feed-body">
								<p>El módulo de reinscripciones es para alumnos. Si nunca has entrado al portal solicita tu calve de activación en fb/unisierra y una vez que a tengas da click en el boton de "Acceso Alumnos"</p>
							</div>

						</div>
						
					</div>

					<div class="feed">

						<div class="feed_content">

							<div class="feed-header">
								<h1>#Nuevas Formas de Pago</h1>
							</div>

							<div class="feed-body">
								<p> Ahora puedes pagar tu Inscripción con depósito en el banco, con tu tarjeta de débito/crédito, con transferencia o en Efectivo pagando en OXXO. No olvides que algunos métodos de pago aplican comisión.</p>
							</div>

						</div>
						
					</div>

					<div class="feed">

						<div class="feed_content">

							<div class="feed-header">
								<h1>#Usuarios iPhone</h1>
							</div>

							<div class="feed-body">
								<p>Al momento de gestionar tus pagos asegurate de usar un navegador seguro como Chrome, Firexfox, Brave u Opera en tu móvil, de lo contrario el portal devolverá un error al intentar pagar.</p>
							</div>

						</div>
						
					</div>

				</div>

				<div class="col-md-12">

					<img src="{{asset('img/temple/unisierra.png')}}" class="osos_alfa">

				</div>

			</div>

		</div>

		<div class="col-md-6">

			<div class="card card-custom">

				<form method="post" action="{{route('alumn.users.registerAlumn')}}" style="width: 90%; margin-right: auto; margin-left: auto">

					{{ csrf_field() }}

					<div class="card-body">

						<div class="row">

							<h3>Registro Nuevo Ingreso</h3>

						</div>

						<div class="row" style="padding-top: 1rem">

							<div class="col-md-6">
								
								<div class="input-group mb-3">

								  	<label class="field a-field a-field_a2">

									    <input class="field__input a-field__input" placeholder="Ingrese su nombre" id="name" name="name" required value="{{ old('name') }}">

									    <span class="a-field__label-wrap">

									        <span class="a-field__label">Nombre (s)</span>

									    </span>

									</label> 

								</div>

							</div>

							<div class="col-md-6">
								
								<div class="input-group mb-3">

								  	<label class="field a-field a-field_a2">

									    <input class="field__input a-field__input" placeholder="Ingrese su apellido" id="lastname" name="lastname" value="{{ old('lastname') }}" required>

									    <span class="a-field__label-wrap">

									        <span class="a-field__label">Apellido (s)</span>

									    </span>

									</label> 

								</div>

							</div>

							<div class="col-md-12">
								
								<div class="input-group mb-3">

								  	<label class="field a-field a-field_a2">

									    <input type="email" class="field__input a-field__input" placeholder="e.g Example@example.com" id="email" name="email" required>

									    <span class="a-field__label-wrap">

									        <span class="a-field__label">Correo</span>

									    </span>

									</label> 

								</div>

							</div>

							<div class="col-md-6">
								
								<div class="input-group mb-3">

								  	<label class="field a-field a-field_a2">

									    <input type="password" class="field__input a-field__input" placeholder="Ingrese una contraseña" id="first" required>

									    <span class="a-field__label-wrap">

									        <span class="a-field__label">Contraseña</span>

									    </span>

									</label> 

								</div>

							</div>

							<div class="col-md-6">
								
								<div class="input-group mb-3">

								  	<label class="field a-field a-field_a2">

									    <input type="password" class="field__input a-field__input" placeholder="Ingrese una contraseña" id="password" name="password" required>

									    <span class="a-field__label-wrap">

									        <span class="a-field__label">Confirmar contraseña</span>

									    </span>

									</label> 

								</div>

							</div>

						</div>

					</div>
						
					<div class="row footer-custom">

						<div class="col-md-12">

							<button type="button" class="btn btn-primary btn-block boton sent">Guardar</button>
							
						</div>

					</div>

				</form>


			</div>


			<div class="btns-row">
				<a href="{{route('alumn.home')}}" 
						class="btn btn-success btn-lg btn-block " 
						style="color: white; border-radius: 20px; margin:0rem 2rem; font-weight: 900;font-size: 20px;">
				 Acceso
				 Alumnos
				</a>
				<a href="{{route('alumn.home')}}" 
						class="btn btn-warning btn-lg btn-block button-custom my-2 my-sm-0" 
						style="color: white; border-radius: 20px;  margin:0rem 2rem; font-weight: 900;font-size: 20px;">
				Acesso Nuevo
				Ingreso
				</a>
			</div>

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
    });
</script>

<script src="{{asset('js/website/home.js')}}"></script>

@stop