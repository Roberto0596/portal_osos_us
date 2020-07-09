@extends('Alumn.main')

@section('content-alumn')
<head>
  <link rel="stylesheet" href="{{ asset('css/card.css') }}"> 

  <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Inconsolata'>
  <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans'>
</head>
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

                      <div class="front-card">

                        <img src="{{asset('img/alumn/payment methods/card.png')}}" alt="" class="card-image-rob">
                        <h4 class="titulo-cards">Pago con tarjeta</h4>
                        
                      </div>
                      
                      <div class="back-card">

                        <button id="payment-card" class="btn btn-success">Paga con tu cuenta</button>

                      </div> 

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

                        <div class="front-card">

                          <img src="{{asset('img/temple/avatar.jpg')}}" alt="" class="card-image-rob">
                          <h4 class="titulo-cards">Pago con deposito bancario</h4>
                          
                        
                        </div>

                        <div class="back-card">

                          <p>HOLA</p>
                          <button class="btn btn-success">Realiza un pago en oxxo</button>

                        </div>

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

                      <div class="front-card">

                        <img src="{{asset('img/temple/avatar.jpg')}}" alt="" class="card-image-rob">
                        <h4 class="titulo-cards">Pago con transferencia interbancaria</h4>
                       

                      </div>
                      
                      <div class="back-card">

                        <button class="btn btn-success">Realiza una transferencia</button>
                      
                      </div>

                    </div>
                      
                  </form>

              </div>

          </div>

            <div class="col-md-3 col-sm-12" style="margin-bottom: 55px;">

              <div class="container-custom">

                      <div class="card2">

                        <div class="front-card">

                          <img src="{{asset('img/temple/avatar.jpg')}}" alt="" class="card-image-rob">
                          <h4 class="titulo-cards">transferencia o deposito bancario</h4>
                         
                        </div>
                        
                        <div class="back-card">

                          <button class="btn btn-success" data-toggle="modal" data-target="#modalTicket">subir comprobante</button>

                        </div>

                      </div>

              </div>

            </div>
          
        </div>

      </div>

    </div>

      <div class="card-footer">

        <div class="row">

          <div class="col-md-5">

              <p style="font-size: 30px">Total: <span>{{$total}}</span></p>

          </div>

          <div class="col-md-1">

            <form target="_blank"  method="POST" action="{{ route('alumn.fichas',['digital','ver','transferencia'])}}" style="width: 40%; margin: 5%;">
              @csrf             
              <button type="submit" class="btn btn-primary" style="background-color: orange; border: none; float: left; margin: 10%">Transferencia</button>
            </form>

          </div>

          <div class="col-md-1">

           <form target="_blank"  method="POST" action="{{ route('alumn.fichas',['digital','ver','deposito'])}}" style="width: 40%; margin: 5%;">
              @csrf              
              <button type="submit" class="btn btn-primary" style="background-color: orange; border: none; float: left; margin: 10%">Depósito</button>
            </form>

          </div>

          <div class="col-md-5">

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
                <div></div>
              </div>
              <div class="card-expiration-date">
                <label>VENCE FIN DE</label>
                <div></div>
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
        <form id="card-form" action="{{route('alumn.pay.card')}}" method="POST" autocomplete="off" novalidate>
          {{ csrf_field() }}
          <input id="full-card-number" data-conekta="card[number]" type="hidden" value="">
          <fieldset>
            <label for="card-number">NÚMERO DE TARJETA</label>
            <input type="num" id="card-number"   class="input-cart-number" maxlength="4" />
            <input type="num" id="card-number-1" class="input-cart-number" maxlength="4" />
            <input type="num" id="card-number-2" class="input-cart-number" maxlength="4" />
            <input type="num" id="card-number-3" class="input-cart-number" maxlength="4" />
          </fieldset>
          <fieldset>
            <label for="card-holder">NOMBRE DEL PROPIETARIO</label>
            <input data-conekta="card[name]" class="form-control" type="text" id="card-holder" />
          </fieldset>
          <fieldset class="card-expire">
            <label for="expire-month">FECHA DE EXPIRACIÓN</label>
            <div class="select">
              <select ata-conekta="card[exp_month]" class="form-control" id="expire-month">
                <option></option>
                <option>01</option>
                <option>02</option>
                <option>03</option>
                <option>04</option>
                <option>05</option>
                <option>06</option>
                <option>07</option>
                <option>08</option>
                <option>09</option>
                <option>10</option>
                <option>11</option>
                <option>12</option>
              </select>
            </div>
            <div class="select">
              <select data-conekta="card[exp_year]" class="form-control" id="expire-year">
                <option></option>
                <option>2016</option>
                <option>2017</option>
                <option>2018</option>
                <option>2019</option>
                <option>2020</option>
                <option>2021</option>
                <option>2022</option>
                <option>2023</option>
                <option>2024</option>
                <option>2025</option>
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
          </div>
        </form>
      </div>
    
    </div>

  </div>

</div>

<div class="modal fade" id="modalTicket">

  <div class="modal-dialog modal-lg">

    <div class="modal-content">

    	<div class="modal-header">

    		<h3>Deposito en banco o transferencia bancara</h3>

    	</div>
        
        <div class="modal-body">

          <form action="{{route('alumn.pay.upload')}}" method="post" enctype="multipart/form-data">
              
            {{ csrf_field() }}

            <div class="row">

              <div class="col-md-12">
                  <p>Para procesar tu pago, es necesario que realices el deposito o transferencia a la cuenta de la universidad y despues subas el comprobante en esta parte. una vez que lo hayas hecho, personal de finanzas verificaran el pago y te dejaran avanzar en el proceso</p>
              </div>
              
            </div>

            <div class="row">

              <div class="col-md-12">
                <div class="form-group">

                <div class="panel">SUBIR COMPROBANTE</div>

                  <input type="file" name="file" id="ticket">

                </div>

              </div>

            </div>

            <div class="row">

            	<div class="col-md-12">

            		<div class="form-group" id="pay-now" style="margin-top: 10vh;">

		                <button class="btn btn-success" type="submit">subir</button>
		              
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
