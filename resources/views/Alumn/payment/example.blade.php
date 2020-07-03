@extends('Alumn.main')

@section('content-alumn')

<div class="content-wrapper">

  <section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1>Elige tu carga academica</h1>

        </div>

        <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right">

            <li class="breadcrumb-item"><a href="#">Home</a></li>

            <li class="breadcrumb-item active">Carga academica</li>

          </ol>

        </div>

      </div>

    </div>

  </section>

  <section class="content">

    <div class="card card-success" style="height: 80vh;">

      <div class="card-header nav-custom-green">

        <h3 class="card-title">Selecciona alguna de las siguientes opciones</h3>

      </div>
      
      <div class="card-body scroll-charge">

        <div class="row">

          <div class="col-md-12">

              <div class="text-center">
                  
                  <h3>Total a pagar: 1950</h3>

              </div>

          </div>

        </div>

        <div class="row">

          <div class="col-md-3 col-sm-12" style="margin-bottom: 55px;">

            <div class="container-custom">

                <a href="{{route('alumn.payment.card')}}">

                    <div class="card2">

                        <img src="{{asset('img/temple/avatar.jpg')}}" alt="" class="card-image-rob">
                        <h4 class="titulo-cards">Pago con tarjeta</h4>
                        <p class="parrafo">Paga con tu cuenta</p>

                    </div>
                </a>

            </div>

          </div>

          <div class="col-md-3 col-sm-12" style="margin-bottom: 55px;">

            <div class="container-custom">

                <a href="">

                    <div class="card2">

                        <img src="{{asset('img/temple/avatar.jpg')}}" alt="" class="card-image-rob">
                        <h4 class="titulo-cards">Pago con deposito bancario</h4>
                        <p class="parrafo">Deposita en nuestra cuenta</p>

                    </div>
                </a>

            </div>

          </div>

          <div class="col-md-3 col-sm-12" style="margin-bottom: 55px;">

              <div class="container-custom">

                  <a href="">

                      <div class="card2">

                          <img src="{{asset('img/temple/avatar.jpg')}}" alt="" class="card-image-rob">
                          <h4 class="titulo-cards">Pago con transferencia interbancaria</h4>
                          <p class="parrafo">Realiza una transferencia</p>

                      </div>
                  </a>

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

      <div class="card-footer footer-orange">
        Elige tu forma de pago
      </div>

    </div>

  </section>

</div>

@stop
