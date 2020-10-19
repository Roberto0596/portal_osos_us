@extends('FinancePanel.main')

@section('content-finance')

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Bienvenido <small>{{Auth::guard("finance")->user()->name}}</small></h1>
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
          <div class="col-md-12">
            <h4>Tablero</h4>
          </div>

          <div class="col-md-3 col-sm-12">

            <div class="small-box {{$debits == 0 ? 'bg-default' : 'bg-info'}}">

              <div class="inner">

                <h3>Adeudos</h3>

                <p>{{$debits == 0 ? 'sin adeudos' : 'hay '.$debits.' nuevos adeudos'}}</p>

              </div>

              <div class="icon">

                <i class="fas fa-credit-card"></i>

              </div>

              <a href="{{ route('finance.debit') }}" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>

            </div>
            
          </div>

        </div>

      </div>

    </div>

  </section>

</div>

@stop
