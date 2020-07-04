@extends('Alumn.main')

@section('content-alumn')

<script type="text/javascript" src="https://cdn.conekta.io/js/latest/conekta.js"></script>

<div class="content-wrapper">

  <section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1 id="payment_title">Adeudos pendientes</h1>

        </div>

        <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right">

            <li class="breadcrumb-item"><a href="#">Home</a></li>

            <li class="breadcrumb-item active">Pagos</li>

          </ol>

        </div>

      </div>

    </div>

  </section>

  <section class="content">

    <div class="card card-success" style="height: 75vh;">

      <div class="card-header">

        <div class="row">

          <div class="col-12">

            <h4>
              <i class="fas fa-globe"></i> Unisierra
              <small class="float-right">Date: {{date("Y-m-d")}}</small>
            </h4>

          </div>

        </div>

      </div>
     
      <div class="card-body scroll-charge">

        <div class="row" id="hidden-1">

          <table class="table">

            <thead>
              <tr>
                <th>#</th>
                <th>concepto</th>
                <th>Encargado</th>
                <th>Fecha</th>
                <th>Sub total</th>
              </tr>
            </thead>

            <tbody>
              @foreach($debit as $key => $value)
                <tr>
                  <td>{{($key+1)}}</td>
                  <td>{{$value->concept}}</td>
                  <td>{{selectAdmin($value->admin_id)->name}}</td>
                  <td>{{$value->created_at}}</td>
                  <td>{{$value->amount}}</td>
                </tr>
              @endforeach
            </tbody>

          </table>
          
        </div>

        <div class="row" id="hidden-2">
          
          <div class="row">

          <div class="col-md-3 col-sm-12" style="margin-bottom: 55px;">

            <div class="container-custom">

                <div style="cursor: pointer;">

                    <div class="card2">

                        <img src="{{asset('img/temple/avatar.jpg')}}" alt="" class="card-image-rob">
                        <h4 class="titulo-cards">Pago con tarjeta</h4>
                        <button id="payment-card" class="btn btn-success">Paga con tu cuenta</button>

                    </div>
                </div>

            </div>

          </div>

          <div class="col-md-3 col-sm-12" style="margin-bottom: 55px;">

            <form action="{{ route('alumn.pay.cash') }}" method="post">

              {{ csrf_field() }}

              <div class="container-custom">

                  <a href="{{ route('alumn.pay.cash') }}">

                      <div class="card2">

                          <img src="{{asset('img/temple/avatar.jpg')}}" alt="" class="card-image-rob">
                          <h4 class="titulo-cards">Pago con deposito bancario</h4>
                          <button class="btn btn-success">Realiza un pago en oxxo</button>

                      </div>
                  </a>

              </div>

            </form>

          </div>

          <div class="col-md-3 col-sm-12" style="margin-bottom: 55px;">

              <div class="container-custom">

                  <form method="post" action="{{route('alumn.pay.stei')}}">

                    {{ csrf_field() }}

                    <div class="card2">

                        <img src="{{asset('img/temple/avatar.jpg')}}" alt="" class="card-image-rob">
                        <h4 class="titulo-cards">Pago con transferencia interbancaria</h4>
                        <button class="btn btn-success">Realiza una transferencia</button>

                    </div>
                      
                  </form>

              </div>

          </div>

            <div class="col-md-3 col-sm-12" style="margin-bottom: 55px;">

              <div class="container-custom">

                  <a href="">

                      <div class="card2">

                          <img src="{{asset('img/temple/avatar.jpg')}}" alt="" class="card-image-rob">
                          <h4 class="titulo-cards">Realizar un acuerdo con la institución</h4>
                          <p class="parrafo">Habla con nosotross</p>

                      </div>
                  </a>

              </div>

            </div>
          
        </div>

        </div>

      </div>

      <div class="card-footer">

        <div class="row">

          <div class="col-md-6">

              <p style="font-size: 30px">Total: <span>{{$total}}</span></p>

          </div>

          <div class="col-md-6">

            <div class="float-right">

              <button type="button" class="btn btn-danger" id="extra" style="border-radius: 20px">no debo eso <i class="fa fa-hand-stop-o"></i></button>

              <button type="button" class="btn btn-warning" style="border-radius: 20px; display: none;color: white" id="back" ><i class="fa fas  fa-arrow-circle-left" style="color: white !important"></i> Regresar</button>

              <button type="button" class="btn btn-success" style="border-radius: 20px" id="next" >Siguiente <i class="fa fas  fa-arrow-circle-right"></i></button>

            </div>

          </div>

        </div>

      </div>

    </div>

  </section>

</div>

<div class="modal fade" id="modalCard">

  <div class="modal-dialog modal-lg">

    <div class="modal-content">
        
        <div class="modal-body">

          <form id="card-form" action="{{route('alumn.pay.card')}}" method="post">
              
            {{ csrf_field() }}

            <div class="card">

              <div class="card-body">

                <div class="creditCardForm">

                  <div class="heading">

                      <h1>Pago en linea</h1>

                  </div>

                  <div class="payment">

                    {{ csrf_field() }}

                    <div class="form-group owner">
                        <label for="name">Nombre del tarjetahabiente</label>
                        <input data-conekta="card[name]" type="text" class="form-control" name="name" id="name">
                    </div>

                    <div class="form-group" id="card-number-field">
                        <label for="cardNumber">Numero de tarjeta</label>
                        <input data-conekta="card[number]" value="4242424242424242" class="form-control">
                    </div>

                    <div class="form-group CVV">
                        <label for="cvv">CVC</label>
                        <input data-conekta="card[cvc]" class="form-control" maxlength="4">
                    </div>

                    <br>

                    <div class="form-group" id="expiration-date">
                        <label>Fecha de expiracion</label>
                        <input data-conekta="card[exp_month]" class="form-control" maxlength="2">

                        <input data-conekta="card[exp_year]" class="form-control" maxlength="4">
                        
                    </div>

                    <div class="form-group" id="pay-now">

                        <button class="btn btn-default" id="confirm-purchase">Confirm</button>
                    
                    </div>

                  </div>

                </div>

              </div>
              
            </div>

          </form>

        </div>

    </div>

  </div>

</div>

<script src="{{asset('js/alumn/payment.js')}}"></script>

@stop
