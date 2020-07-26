@extends('Alumn.main')

@section('content-alumn')

  
<link rel="stylesheet" href="{{asset('css/oxxo_pay.css')}}">

<div class="content-wrapper">

  <div class="row">

    <div class="col-md-12">

      <div style="float: right; padding: 8px;">

        <button class="btn btn-success" id="print">Imprimir</button>

      </div>

      <div style="float: right; padding: 8px;">

        <form method="POST" action="{{ route('alumn.pay.rollback',$order['id_order']) }}" id="form-rollback">

          {{ csrf_field() }}

          <input type="hidden" value="ok" name="validator">

          <button class="btn btn-danger">Usar otro método</button>

        </form>

      </div>

    </div>

  </div>

  <section class="content">

    <div class="card">

      <div class="card-header">

        <p>nota:</p>

      </div>

      <div class="card-body">

        <div class="row">

          <div class="col-md-12">

            <p>Hola {{ Auth::guard("alumn")->user()->name}}, para seguir con el proceso de inscipcion ve a tu tienda oxxo mas cercana y realiza el pago correspondiente. Después de que la encargada de finanzas verifique el pago podras continuar.</p>

          </div>

        </div>

      </div>

    </div>
       
    <div class="opps">
      <div class="opps-header">
        <div class="opps-reminder">Ficha digital. No es necesario imprimir.</div>
        <div class="opps-info">
          <div class="opps-brand"><img src="{{asset('img/temple/oxxopay.png')}}" alt="OXXOPay"></div>
          <div class="opps-ammount">
            <h3>Monto a pagar</h3>
            <h2>$ {{number_format(($order["total"]/100),2)}}<sup>MXN</sup></h2>
            <p>OXXO cobrará una comisión adicional al momento de realizar el pago.</p>
          </div>
        </div>
        <div class="opps-reference">
          <h3 id="referencia">Referencia</h3>
          <h1 class="h1-oxxo">{{ $order["reference"] }}</h1>
        </div>
      </div>
      <div class="opps-instructions">
        <h3>Instrucciones</h3>
        <ol>
          <li>Acude a la tienda OXXO más cercana. <a href="https://www.google.com.mx/maps/search/oxxo/" target="_blank">Encuéntrala aquí</a>.</li>
          <li>Indica en caja que quieres realizar un pago de <strong>OXXOPay</strong>.</li>
          <li>Dicta al cajero el número de referencia en esta ficha para que tecleé directamete en la pantalla de venta.</li>
          <li>Realiza el pago correspondiente con dinero en efectivo.</li>
          <li>Al confirmar tu pago, el cajero te entregará un comprobante impreso. <strong>En el podrás verificar que se haya realizado correctamente.</strong> Conserva este comprobante de pago.</li>
        </ol>
        <div class="opps-footnote">Después de realizado el pago, revise el estatus de su Inscripción 24hrs después, el cual, debe cambiar a Inscrito.</div>
      </div>

    </div> 
    
  </section>

</div>

<script>

  $("#print").click(function(){
    window.print();
  });

  $("#form-rollback").submit(function(e)
  {
    e.preventDefault();
    var $form = $("#form-rollback");
    swal.fire({
        title: '¿estas seguro de cancelar oxxopay?',
        text: "¡solo puedes cancelar una vez!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, estoy seguro'
    }).then((result)=>
    {
      if (result.value)
      {
        $form.get(0).submit();
      }
    })
  });

</script>

@stop
