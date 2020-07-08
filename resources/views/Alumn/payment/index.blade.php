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

    	<div class="modal-header">
    		<h1>Pago en linea</h1>
    	</div>
        
        <div class="modal-body">

          <form id="card-form" action="{{route('alumn.pay.card')}}" method="post">
              
            {{ csrf_field() }}

            <div class="row">

                <div class="col-md-12">                

                    <div class="form-group row">

                      <label for="inputName" class="col-sm-2 col-form-label">Name</label>

                      <div class="col-sm-10">

                        <input data-conekta="card[name]" type="text" class="form-control" name="name" id="name">

                      </div>

                    </div>

                 </div>

                <div class="col-md-12">

                    <div class="form-group row">

                      	<label for="inputName" class="col-sm-2 col-form-label">Numero de tarjeta</label>

	                    <div class="col-sm-10">

	                      <input data-conekta="card[number]" value="4242424242424242" class="form-control">

	                    </div>

                    </div>

                </div>

                <div class="col-md-12">

                  <div class="form-group row">

                    <label for="inputName" class="col-sm-2 col-form-label">CVC</label>

                    <div class="col-sm-10">

                      <input data-conekta="card[cvc]" class="form-control" maxlength="4">

                    </div>

                  </div>

                 </div>

                <div class="row">
                    
                    <div class="col-md-4">
                    	<label for="inputName" class="col-sm-12 col-form-label">Fecha de expiracion</label>
                    </div>

                    <div class="col-md-4">

                    	<div class="form-group row">

		                    <div class="col-sm-10">

		                      <input data-conekta="card[exp_month]" class="form-control" maxlength="2">

		                    </div>

		                </div>
	                                            
                    </div>
                    
                    <div class="col-md-4">

                    	<div class="form-group row">

		                    <div class="col-sm-10">

		                      <input data-conekta="card[exp_year]" class="form-control" maxlength="4">

		                    </div>

		                </div>
                                          
                    </div>
                    
                </div>
                  
            </div>

            <div class="row">
            	<div class="col-md-12">
            		<div class="form-group" id="pay-now" style="margin-top: 10vh;">

		                <button class="btn btn-success" id="confirm-purchase">Pagar</button>
		              
		            </div>
            	</div>
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

          <form id="card-form" action="{{route('alumn.pay.upload')}}" method="post" enctype="multipart/form-data">
              
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

		                <button class="btn btn-success" id="confirm-purchase">subir</button>
		              
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
