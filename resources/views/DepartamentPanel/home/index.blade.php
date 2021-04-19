@extends('DepartamentPanel.main')

@section('content-departament')

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Bienvenido {{Auth::guard("departament")->user()->name}}</h1>
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

        <div class="row">
          
          <div class="col-md-4 col-sm-12">

            <div class="small-box bg-success">

              <div class="inner">

                <h3>Equipos ocupados</h3>

                <p>{{ $equipments }}</p>

              </div>

              <!-- i -->

              <a href="" class="small-box-footer" data-toggle='modal' data-target='#modalPeriod'>Ver <i class="fas fa-arrow-circle-right"></i></a>

            </div>
            
          </div>

        </div>

      </div>

    </div>

  </section>

</div>

@stop
