@extends('Alumn.main')

@section('content-alumn')

  
<link rel="stylesheet" href="{{asset('css/spei_pay.css')}}">

<div class="content-wrapper">

  <div class="row">
    <div class="col-md-12">
      <div style="float: right; padding: 8px;">
        <button class="btn btn-success" id="print">Imprimir</button>
      </div>
    </div>
  </div>
       
  <div class="ps">
      <div class="ps-header">
        <div class="ps-reminder">Ficha digital. No es necesario imprimir.</div>
        <div class="ps-info">
          <div class="ps-brand"><img src="{{asset('img/temple/spei_brand.png')}}" alt="Banorte"></div>
          <div class="ps-amount">
            <h3>Monto a pagar</h3>
            <h2>$ {{$order["total"]}} <sup>MXN</sup></h2>
            <p>Utiliza exactamente esta cantidad al realizar el pago.</p>
          </div>
        </div>
        <div class="ps-reference">
          <h3>CLABE</h3>
          <h1>{{$order["reference"]}}</h1>
        </div>
      </div>
      <div class="ps-instructions">
        <h3>Instrucciones</h3>
        <ol>
          <li>Accede a tu banca en línea.</li>
          <li>Da de alta la CLABE en esta ficha. <strong>El banco deberá de ser STP</strong>.</li>
          <li>Realiza la transferencia correspondiente por la cantidad exacta en esta ficha, <strong>de lo contrario se rechazará el cargo</strong>.</li>
          <li>Al confirmar tu pago, el portal de tu banco generará un comprobante digital. <strong>En el podrás verificar que se haya realizado correctamente.</strong> Conserva este comprobante de pago.</li>
        </ol>
        <div class="ps-footnote">Al completar estos pasos recibirás un correo de <strong>Nombre del negocio</strong> confirmando tu pago.</div>
      </div>
    </div>  

</div>

<script>
    $("#print").click(function(){
      window.print();
    })
</script>

@stop
