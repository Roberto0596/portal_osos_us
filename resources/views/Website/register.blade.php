@extends('Website.main')

@section('main-content')

<div class="back2">

	<div class="row" style="margin: 1%">

		<div class="col-md-8">

			<div class="row">

				<div class="col-md-12 osos-title">
					<h1>Se un Oso Unisierra</h1>
				</div>

				<div class="col-md-12">

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

					<div class="feed">

						<div class="feed_content">

							<div class="feed-header">
								<h1>encabezado3</h1>
							</div>

							<div class="feed-body">
								<p>Parrafo</p>
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

				<div class="card-header text-center">

					<h3>Se parte de nosotros</h3>

				</div>


				<form action="" style="width: 80%; margin-right: auto; margin-left: auto">

					<div class="card-body">

						<div class="row">

							<div class="col-md-12">
								
								<div class="input-group mb-3">

								  	<label class="field a-field a-field_a2">

									    <input class="field__input a-field__input" placeholder="Ingrese su nombre" id="name" name="name" required>

									    <span class="a-field__label-wrap">

									        <span class="a-field__label">Nombre (s)</span>

									    </span>

									</label> 

								</div>

							</div>

							<div class="col-md-12">
								
								<div class="input-group mb-3">

								  	<label class="field a-field a-field_a2">

									    <input class="field__input a-field__input" placeholder="Ingrese su apellido" id="lastName" name="lastName" required>

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

									    <input type="email" class="field__input a-field__input" placeholder="Ingrese una contraseña" id="password" name="password" required>

									    <span class="a-field__label-wrap">

									        <span class="a-field__label">Contraseña</span>

									    </span>

									</label> 

								</div>

							</div>

						</div>

					</div>
						
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12 col-custom">
								<button class="btn btn-warning button-custom">Ser aspirante</button>
							</div>
						</div>
					</div>

				</form>


			</div>

		</div>

	</div>

</div>

<script src="{{asset('js/website/home.js')}}"></script>

@stop