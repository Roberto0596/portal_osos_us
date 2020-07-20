@extends('FinancePanel.main')

@section('content-finance')

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Bienvenido {{Auth::guard("finance")->user()->name}}</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active"><a href="#">Home</a></li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">

    <div class="card">
      
      <div class="card-body">

       	<div class="container">
			<div class="row">
				<div class="col-md-3">
					<a class="btn btn-success" href="{{route('finance.load')}}">LLenar Tabla</a>
				</div>
				<div class="col-md-3">
					<a class="btn btn-success" href="{{route('finance.generate')}}">Generar grupos</a>
				</div>
				<div class="col-md-3">
					<a class="btn btn-success" href="{{route('finance.pdf')}}" target="_blank">Imprimir</a>
				</div>
				<div class="col-md-3">
					<a class="btn btn-danger" href="{{route('finance.deleteGroups')}}">borrar grupos</a>
				</div>
			</div>
		</div>

      </div>

    </div>

  </section>

</div>

@endsection