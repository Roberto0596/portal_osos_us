@extends('Website.main')

@section('main-content')

<div class="back2">

	<div class="row" style="margin: 1%">

		<div class="col-md-8">

			<div class="row">

				<div class="col-md-12 osos-title">
					<h1 class="feel-title">Se un Oso Unisierra</h1>
				</div>

				<div class="col-md-12">

					<div class="feed">

						<div class="feed_content">

							<div class="feed-header">
								<h1>#Inscripciones.</h1>
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
								<p>El módulo de Re-Inscripciones ya está abierto. Si nunca has entrado al portal da Clic en el botón de la esquina “Acceder”  y sigue las instrucciones para activar tu cuenta con el usuario y contraseña que tu TUTOR te entregó.</p>
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

				</div>

				<div class="col-md-12">

					<img src="{{asset('img/temple/unisierra.png')}}" class="osos_alfa">

				</div>

			</div>

		</div>

		<div class="col-md-4">

			<div class="card card-custom">

				<form method="post" action="{{route('alumn.users.registerAlumn')}}" style="width: 80%; margin-right: auto; margin-left: auto">

					{{ csrf_field() }}

					<div class="card-body">

						<div class="row">

							<h3>Registrarme en el Portal</h3>

						</div>

						<div class="row">

							<div class="col-md-12">
								
								<div class="input-group mb-3">

								  	<label class="field a-field a-field_a2">

									    <input class="field__input a-field__input" placeholder="Ingrese su nombre" id="name" name="name" required value="{{ old('name') }}">

									    <span class="a-field__label-wrap">

									        <span class="a-field__label">Nombre (s)</span>

									    </span>

									</label> 

								</div>

							</div>

							<div class="col-md-12">
								
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

							<div class="col-md-12">
								
								<div class="input-group mb-3">

								  	<label class="field a-field a-field_a2">

									    <input type="password" class="field__input a-field__input" placeholder="Ingrese una contraseña" id="first" required>

									    <span class="a-field__label-wrap">

									        <span class="a-field__label">Contraseña</span>

									    </span>

									</label> 

								</div>

							</div>

							<div class="col-md-12">
								
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

						<div class="col-md-12 col-custom">

							<button type="button" class="btn btn-warning button-custom sent">Registrarse como aspirante</button>
							
						</div>

					</div>

				</form>


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
    })
</script>

<script src="{{asset('js/website/home.js')}}"></script>

@stop