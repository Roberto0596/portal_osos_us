@extends('Alumn.main')

@section('content-alumn')

<script type="text/javascript" src="https://cdn.conekta.io/js/latest/conekta.js"></script>

<div class="content-wrapper">
  
  <section class="content" style="margin-top: 1vh">

    <div class="card">

      <div class="card-body">

        <div class="creditCardForm">

          <div class="heading">

              <h1>Pago en linea</h1>

          </div>

          <div class="payment">

              <form id="card-form" method="post" action="{{route('alumn.pay.card')}}">

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

                  <input type="hidden" id="conektaTokenId" name="conektaTokenId" >

              </form>

          </div>

        </div>

      </div>
      
    </div>

  </section>
  
</div>

<script src="{{asset('js/alumn/payment.js')}}"></script>

@stop
