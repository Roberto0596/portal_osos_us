@extends('Alumn.main')

@section('content-alumn')

  
<link rel="stylesheet" href="{{asset('css/oxxo_pay.css')}}">

<div class="content-wrapper">

  <section class="content-header">
      
      <div class="container-fluid">
        
        <div class="row mb-2">
          
          <div class="col-sm-6">
            
            <h1>Hola {{ucwords(strtolower(Auth::guard("alumn")->user()->name))}}</h1>
            
          </div>
          
        </div>
        
      </div>
      
  </section>

  <section class="content">

      <div class="card">

        <div class="card-body">

          <div class="container">

            <div class="row">

              <div class="col-md-12">

                <p>Hola, recibimos tu comprobante, ahora debes esperar a que la encargada de finanzas verifique tu pago</p>

              </div>

            </div>
            
          </div>

        </div>

      </div>

  </section>

</div>

<script>
    $("#print").click(function(){
      window.print();
    })
</script>

@stop
