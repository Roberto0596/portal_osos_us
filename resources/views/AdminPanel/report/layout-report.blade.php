@extends('AdminPanel.main')

@section('content-admin')

<div class="content-wrapper">
	
	<section class="content-header">
	    <div class="container-fluid">
	      <div class="row mb-2">
	        <div class="col-sm-6">
	          <h1>Reportes</h1>
	        </div>
	        <div class="col-sm-6">
	          <ol class="breadcrumb float-sm-right">
	            <li class="breadcrumb-item"><a href="#">Home</a></li>
	            <li class="breadcrumb-item active"><a href="#">Resportes</a></li>
	          </ol>
	        </div>
	      </div>
	    </div>
  	</section>

  	<section class="content">

  		<div class="card">

  			<div class="card-header">

  				Opciones

  			</div>

  			<div class="card-body">

  				<div class="row">

  					<div class="col-md-12">

  						<div class="form-group row">

		                    <label for="inputName" class="col-sm-2 col-form-label">Seleccione una opcion</label>

		                    <div class="col-sm-10">

		                    	<select id="report-type" class="form-control">
		                    		<option value="0">Home</option>
		                    		<option value="1">Alumnos inscritos</option>
		                    		<option value="2">Alumnos por grupo</option>
		                    	</select>

		                    </div>

		                </div>

  					</div>

  				</div>

  			</div>

  		</div>

  		@yield('report-content')

	</section>

</div>

@stop