@extends('Website.main')

@section('main-content')

<div class="back2">
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<a class="btn btn-success" href="{{route('home.load')}}">LLenar Tabla</a>
			</div>
			<div class="col-md-3">
				<a class="btn btn-success" href="{{route('home.generate')}}">Generar grupos</a>
			</div>
			<div class="col-md-3">
				<a class="btn btn-success" href="{{route('home.pdf')}}">Imprimir</a>
			</div>
		</div>
	</div>
</div>

@endsection