@extends('Alumn.main')

@section('content-alumn')

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

            <div class="col-md-2 col-sm-12" style="margin-bottom: 55px;">

              <div class="container-custom">

                <div id="payment-card" style="cursor: pointer;">

                  <div class="card2">

                    <figure>

                      <div class=frontal>

                        <img src="{{asset('img/temple/avatar.jpg')}}" alt="" class="card-image-rob">

                        <h4 class="titulo-cards">Pago con tarjeta</h4>

                        <p class="parrafo">Paga con tu cuenta</p>

                      </div>

                      <div class="trasera">

                        <p>
                         Validación automática, el usuario pagará$1950.00 de colegiatura + $70.89 por comisión bancaria, un total de $2’020.89
                        </p>

                      </div>

                    </figure>

                  </div>

                </div>

              </div>

            </div>

            <div class="col-md-2 col-sm-12" style="margin-bottom: 55px;">

              <div class="container-custom">

                <div id="payment-card" style="cursor: pointer;">

                  <div class="card2">

                    <figure>

                     <div class=frontal>

                      <img src="{{asset('img/temple/avatar.jpg')}}" alt="" class="card-image-rob">

                      <h4 class="titulo-cards">Depósito Bancario</h4>

                      <p class="parrafo">Deposito desde Banco Santander</p>

                    </div>

                     <figcaption class="trasera">

                      <p >
                        No tiene comisión el usuario debe depositar
                        en Banco Santander, el sistema emite una hoja de pago con su
                        referencia personal. Usuario envía comprobante de depósito
                        escaneado o fotografiado mediante el sistema. Validación
                        Manual. Se paga $1’950.00
                      </p>

                    </figcaption>

                  </div>

                </div>

              </div>

          </div>

          <div class="col-md-2 col-sm-12" style="margin-bottom: 55px;">

            <div class="container-custom">

              <div id="payment-card" style="cursor: pointer;">

                <div class="card2">

                  <figure>

                    <div class=frontal>

                      <img src="{{asset('img/temple/avatar.jpg')}}" alt="" class="card-image-rob">

                      <h4 class="titulo-cards">Transferencia Interbancaria SPEI</h4>

                      <p class="parrafo">Pago desde banco con SPEI</p>

                    </div>

                    <figcaption class="trasera">

                      <p >
                        Validación automática, el usuario
                        pagará $1’950.00 de colegiatura + $14.0 por comisión bancaria, un total de $1’964.50
                      </p>

                    </figcaption>

                  </div>

                </div>

              </div>

            </div>

            <div class="col-md-2 col-sm-12" style="margin-bottom: 55px;">

              <div class="container-custom">

               <div id="payment-card" style="cursor: pointer;">

                <div class="card2">

                  <figure>

                    <div class=frontal>

                      <img src="{{asset('img/temple/avatar.jpg')}}" alt="" class="card-image-rob">

                      <h4 class="titulo-cards">Pago en efectivo</h4>

                      <p class="parrafo">Pago desde Oxxo</p>

                    </div>

                    <figcaption class="trasera">

                      <p >
                        Validación
                        automática, el usuario pagará $1950.00 de colegiatura +
                        $92.39 por comisión bancaria, un total de $2’042.39
                      </p>

                    </figcaption>

                  </div>

                </div>

              </div>

            </div>

            <div class="col-md-2 col-sm-12" style="margin-bottom: 55px;">

              <div class="container-custom"><div id="payment-card" style="cursor: pointer;">

                  <div class="card2">

                    <figure>

                      <div class=frontal>

                        <img src="{{asset('img/temple/avatar.jpg')}}" alt="" class="card-image-rob">

                        <h4 class="titulo-cards">Pago con tranferencia</h4>

                        <p class="parrafo">Pago desde banca web o movil</p>

                      </div>

                      <figcaption class="trasera">

                        <p class="parrafo">
                         Haga pago desde su banca web o movil,
                         por la cantidad de $1950.00 a banco
                         Santander y subir comprobante de dicha tranferencia
                        </p>

                      </figcaption>

                    </figure>

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

      <form action="{{ route('alumn.pay.card') }}" method="post">

        {{ csrf_field() }}
        
        <div class="modal-body">

        </div>

        <div class="modal-footer justify-content">

          <div class="col-sm container-fluid">

            <div class="row">

              <div class=" col-sm-6 btn-group">

                <button type="button" class="btn btn-danger .px-2 " data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>

              </div>

              <div class=" col-sm-6 btn-group">

                <button class="btn btn-success .px-2"><i class="fa fa-shopping-cart"></i> Pagar</button>
              
              </div>

            </div>

          </div>

        </div>

      </form>

    </div>

  </div>

</div>

<script src="{{asset('js/alumn/payment.js')}}"></script>

@stop
