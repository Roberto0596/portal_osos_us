@extends('Alumn.main')

@section('content-alumn')
<link rel="stylesheet" href="{{ asset('css/card.css') }}"> 
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Inconsolata'>
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans'>
<script type="text/javascript" src="https://cdn.conekta.io/js/latest/conekta.js"></script>
<style>
  .container-card-2 {
      width: 100%;
      max-width: 1200px;
      height: 340px;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      margin: auto;
  }

  .container-card-2 .card2 {
      width: 330px;
      height: 360px;
      border-radius: 8px;
      box-shadow: 0 2px 2px rgba(0,0,0,0.2);
      overflow: hidden;
      text-align: center;
      transition: all 0.25;
      background: #fd7e14;
  }

  .card-image-rob {
      width: 100%;
      height: 185px;
  }

  .titulo-cards {
      text-align: left;
      color: white;
      margin: 10px;
      font-weight: 600;
  }


  .parrafo-card2 {
      text-align: left;
      margin: 10px;
      color: white;
      font-weight: 700;
  }

  .container-card-2 .card2:hover {
      transform: translateY(-15px);
      box-shadow: 0 12px 16px rgba(0,0,0,0.2);
  }

  @media (min-width: 992px){
    .modal-lg, .modal-xl {
        max-width: 950px !important;
    }
  }

</style>

<div class="content-wrapper">

  <section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1>Mis Adeudos</h1>

        </div>

        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('alumn.home')}}">Home</a></li>
            <li class="breadcrumb-item active">Mis Adeudos</li>
          </ol>
        </div>

      </div>

    </div>

  </section>

  @include('chunks.message')

  <section class="content">

    <div class="card" >

      <div style="height: 20rem" class="card-body scroll-charge">

        <table class="table">

          <thead>

            <tr>
              <th style="width: 10px">#</th>
              <th>Concepto</th>
              <th>Descripción</th>
              <th>Monto</th>
              <th>Encargado</th>
              <th>Alumno</th>
              <th>Fecha</th>
              <th>Acciones</th>
            </tr>

          </thead>

          <tbody>

            @foreach($debits as $key => $value)
              <tr>
                <td>{{ ($key+1) }}</td>
                <td>{{ $value->debitType->concept}}</td>
                <td>{{ $value->description}}</td>
                <td>${{ number_format($value->amount,2)}}</td>
                <td>{{ $value->admin->name}}</td>
                <td>{{ current_user()->name }}</td>
                <td>{{ $value->created_at}}</td>
                <td>
                  @if($value->id_order == null)
                   <div class="form-group clearfix">

                    <div class="icheck-success d-inline">

                      <input type="checkbox" class="checkasignatura debit" name="{{ $key }}" id="{{$key}}" debitId="{{ $value->id }}" price="{{$value->amount}}">

                      <label for="{{$key}}">
                          Seleccionar
                      </label>

                    </div>

                  </div>
                  @else
                    <div class="btn-group">
                      <a href="{{route('alumn.debit.note', $value->id_order)}}" class="btn btn-success">Ver referencia</a>
                    </div>
                  @endif
                </td>
              </tr>
            @endforeach

          </tbody>

        </table>

        @if(count($debits) == 0)
        <div class="row" style="margin-top: 5rem;">
          <div class="col-md-12">
            <h4 class="text-center">Sin registros</h4>
          </div>
        </div>
        @endif

      </div>

      <div class="card-footer">
        <div class="row">
          <div class="col-md-6">
            <h1 id="total">0.0</span></h1>
            <input type="hidden" id="total-hidden" value="0">
          </div>
          <div class="col-md-6">
            <button class="btn btn-lg btn-success" style="float: right;" id="payment"><i class="fa fa-check"></i> Pagar</button>
          </div>
        </div>
      </div>

    </div>

  </section>

</div>

<div class="modal fade" id="modal">

  <div class="modal-dialog modal-lg">

    <div class="modal-content" style="background: #e8e8e8;">

      <div class="modal-header">

        <h3>Elija un metodo de pago</h3>

      </div>
        
      <div class="modal-body">

        <div class="row">
          <div class="col-md-3 col-sm-12" style="margin-bottom: 55px;">
              <a href="#">
                <div class="container-card-2">
                  <div class="card2">
                    <img src="{{asset('img/alumn/payment methods/card_white.png')}}" alt="" class="card-image-rob">
                    <h4 class="titulo-cards">Tarjeta</h4>
                    <p class="parrafo-card2">
                      Total: <span id="total-card"></span><br>
                      Total adeudo: <span id="total-adeudo-card"></span><br>
                      Total comisión: <span id="total-comission-card"></span>
                    </p>
                    <button class="btn btn-success" id="card-button">Pagar</button>
                  </div>
                </div>
              </a>
          </div>
          <div class="col-md-3 col-sm-12" style="margin-bottom: 55px;">
              <a href="#">
                <div class="container-card-2">
                  <div class="card2">
                    <img src="{{asset('img/alumn/payment methods/money_white.png')}}" alt="" class="card-image-rob">
                    <h4 class="titulo-cards">OXXO</h4>
                    <p class="parrafo-card2">
                      Total: <span id="total-oxxo"></span><br>
                      Total adeudo: <span id="total-adeudo-oxxo"></span><br>
                      Total comisión: <span id="total-comission-oxxo"></span>
                    </p>
                    <form action="{{ route('alumn.debit.save.oxxo') }}" method="post" id="form-oxxo">
                      {{ csrf_field() }}
                      <input type="hidden" name="debitList" class="debitList">
                      <button class="btn btn-success">Pagar</button>
                    </form>
                  </div>
                </div>
              </a>
          </div>
          <div class="col-md-3 col-sm-12" style="margin-bottom: 55px;">
              <a href="#">
                <div class="container-card-2">
                  <div class="card2">
                    <img src="{{asset('img/alumn/payment methods/transfer_white.png')}}" alt="" class="card-image-rob">
                    <h4 class="titulo-cards">SPEI</h4>
                    <p class="parrafo-card2">
                      Total: <span id="total-spei"></span><br>
                      Total adeudo: <span id="total-adeudo-spei"></span><br>
                      Total comisión: <span id="total-comission-spei"></span>
                    </p>
                    <form action="{{ route('alumn.debit.save.spei') }}" method="post" id="form-spei">
                      {{ csrf_field() }}
                      <input type="hidden" name="debitList" class="debitList">
                      <button class="btn btn-success">Pagar</button>
                    </form>
                  </div>
                </div>
              </a>
          </div>
          <div class="col-md-3 col-sm-12" style="margin-bottom: 55px;">
              <a href="#">
                <div class="container-card-2">
                  <div class="card2">
                    <img src="{{asset('img/alumn/payment methods/bank_white.png')}}" alt="" class="card-image-rob">
                    <h4 class="titulo-cards">Transferencia</h4>
                    <p class="parrafo-card2">
                      Total: <span id="total-transfer"></span><br>
                      Total adeudo: <span id="total-adeudo-transfer"></span><br>
                    </p>
                    <button class="btn btn-success" style="margin-top: 24px;">Pagar</button>
                  </div>
                </div>
              </a>
          </div>
        </div>
            
      </div>

    </div>

  </div>

</div>

<div class="modal fade" id="modalCard">

  <div class="modal-dialog modal-lg">

    <div class="modalcustom-content">

      <div class="checkout">

        <div class="credit-card-box">

          <div class="flip">

            <div class="front">

              <div class="chip"></div>

              <div class="logo">

                <svg version="1.1" id="visa" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                    width="47.834px" height="47.834px" viewBox="0 0 47.834 47.834" style="enable-background:new 0 0 47.834 47.834;">
                  <g>
                    <g>
                      <path d="M44.688,16.814h-3.004c-0.933,0-1.627,0.254-2.037,1.184l-5.773,13.074h4.083c0,0,0.666-1.758,0.817-2.143
                               c0.447,0,4.414,0.006,4.979,0.006c0.116,0.498,0.474,2.137,0.474,2.137h3.607L44.688,16.814z M39.893,26.01
                               c0.32-0.819,1.549-3.987,1.549-3.987c-0.021,0.039,0.317-0.825,0.518-1.362l0.262,1.23c0,0,0.745,3.406,0.901,4.119H39.893z
                               M34.146,26.404c-0.028,2.963-2.684,4.875-6.771,4.875c-1.743-0.018-3.422-0.361-4.332-0.76l0.547-3.193l0.501,0.228
                               c1.277,0.532,2.104,0.747,3.661,0.747c1.117,0,2.313-0.438,2.325-1.393c0.007-0.625-0.501-1.07-2.016-1.77
                               c-1.476-0.683-3.43-1.827-3.405-3.876c0.021-2.773,2.729-4.708,6.571-4.708c1.506,0,2.713,0.31,3.483,0.599l-0.526,3.092
                               l-0.351-0.165c-0.716-0.288-1.638-0.566-2.91-0.546c-1.522,0-2.228,0.634-2.228,1.227c-0.008,0.668,0.824,1.108,2.184,1.77
                               C33.126,23.546,34.163,24.783,34.146,26.404z M0,16.962l0.05-0.286h6.028c0.813,0.031,1.468,0.29,1.694,1.159l1.311,6.304
                               C7.795,20.842,4.691,18.099,0,16.962z M17.581,16.812l-6.123,14.239l-4.114,0.007L3.862,19.161
                               c2.503,1.602,4.635,4.144,5.386,5.914l0.406,1.469l3.808-9.729L17.581,16.812L17.581,16.812z M19.153,16.8h3.89L20.61,31.066
                               h-3.888L19.153,16.8z"/>
                    </g>
                  </g>
                </svg>
              </div>

              <div class="number"></div>

              <div class="card-holder">
                <label>NOMBRE dEL PROPIETARIO</label>
              </div>

              <div class="card-expiration-date">
                <label>VENCE FIN DE</label>
              </div>

            </div>

            <div class="back">

              <div class="strip"></div>

              <div class="logo">
                <svg version="1.1" id="visa" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                    width="47.834px" height="47.834px" viewBox="0 0 47.834 47.834" style="enable-background:new 0 0 47.834 47.834;">
                  <g>
                    <g>
                      <path d="M44.688,16.814h-3.004c-0.933,0-1.627,0.254-2.037,1.184l-5.773,13.074h4.083c0,0,0.666-1.758,0.817-2.143
                               c0.447,0,4.414,0.006,4.979,0.006c0.116,0.498,0.474,2.137,0.474,2.137h3.607L44.688,16.814z M39.893,26.01
                               c0.32-0.819,1.549-3.987,1.549-3.987c-0.021,0.039,0.317-0.825,0.518-1.362l0.262,1.23c0,0,0.745,3.406,0.901,4.119H39.893z
                               M34.146,26.404c-0.028,2.963-2.684,4.875-6.771,4.875c-1.743-0.018-3.422-0.361-4.332-0.76l0.547-3.193l0.501,0.228
                               c1.277,0.532,2.104,0.747,3.661,0.747c1.117,0,2.313-0.438,2.325-1.393c0.007-0.625-0.501-1.07-2.016-1.77
                               c-1.476-0.683-3.43-1.827-3.405-3.876c0.021-2.773,2.729-4.708,6.571-4.708c1.506,0,2.713,0.31,3.483,0.599l-0.526,3.092
                               l-0.351-0.165c-0.716-0.288-1.638-0.566-2.91-0.546c-1.522,0-2.228,0.634-2.228,1.227c-0.008,0.668,0.824,1.108,2.184,1.77
                               C33.126,23.546,34.163,24.783,34.146,26.404z M0,16.962l0.05-0.286h6.028c0.813,0.031,1.468,0.29,1.694,1.159l1.311,6.304
                               C7.795,20.842,4.691,18.099,0,16.962z M17.581,16.812l-6.123,14.239l-4.114,0.007L3.862,19.161
                               c2.503,1.602,4.635,4.144,5.386,5.914l0.406,1.469l3.808-9.729L17.581,16.812L17.581,16.812z M19.153,16.8h3.89L20.61,31.066
                               h-3.888L19.153,16.8z"/>
                    </g>
                  </g>
                </svg>
              </div>

              <div class="ccv">
                <label>CCV</label>
                <div></div>
              </div>

            </div>

          </div>

        </div>

        <form class="card-form" id="card-form" action="{{ route('alumn.debit.save.card') }}" method="POST" autocomplete="off" novalidate>
          {{ csrf_field() }}
          <input type="hidden" name="debitList" class="debitList">
          <input id="fullCardNumber" name="card-number" data-conekta="card[number]" type="hidden" />
          <fieldset>
            <label for="card-number">NÚMERO DE TARJETA</label>
            <input type="num" id="card-number"   class="input-cart-number" maxlength="4" />
            <input type="num" id="card-number-1" class="input-cart-number" maxlength="4" />
            <input type="num" id="card-number-2" class="input-cart-number" maxlength="4" />
            <input type="num" id="card-number-3" class="input-cart-number" maxlength="4" />
          </fieldset>
          <fieldset>
            <label for="card-holder">NOMBRE DEL PROPIETARIO</label>
            <input data-conekta="card[name]"  class="form-control" type="text" id="card-holder" />
          </fieldset>
          <fieldset class="card-expire">
            <label for="expire-month">FECHA DE EXPIRACIÓN</label>
            <div class="select">
              <select data-conekta="card[exp_month]" class="form-control" id="expire-month">
                <option></option>
                <option value="01">01</option>
                <option value="02">02</option>
                <option value="03">03</option>
                <option value="04">04</option>
                <option value="05">05</option>
                <option value="06">06</option>
                <option value="07">07</option>
                <option value="08">08</option>
                <option value="09">09</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
              </select>
            </div>
            <div class="select">
              <select data-conekta="card[exp_year]"  class="form-control" id="expire-year">
                <option></option>
                <option value="2019">2019</option>
                <option value="2020">2020</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
                <option value="2027">2027</option>
                <option value="2028">2028</option>
                <option value="2029">2029</option>
                <option value="2030">2030</option>
              </select>
            </div>
          </fieldset>
          <fieldset class="fieldset-ccv">
            <label for="card-ccv">CCV</label>
            <input type="password" id="card-ccv" maxlength="3" data-conekta="card[cvc]" class="form-control" />
          </fieldset >

          <fieldset  >
            <div class="row offset-2" id="pay-now" style="margin-top: 5%" >
              <button class="btn btn-success" style="margin-right: 20%" data-dismiss="modal" >CANCELAR</button>
              <button class="btn btn-success" id="confirm-purchase">PAGAR</button>
            </div>
          </fieldset>

        </form>

      </div>
    
    </div>

  </div>

</div>


<script src="{{ asset('js/alumn/debit.js')}}"></script>

@stop