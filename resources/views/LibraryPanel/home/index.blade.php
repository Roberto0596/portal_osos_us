@extends('LibraryPanel.main')

@section('content-library')

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Bienvenido {{Auth::guard("library")->user()->name}}</h1>
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

        Proximamente este sera el panel

      </div>

    </div>

  </section>

</div>

@stop
