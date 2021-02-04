<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Pago</title>
</head>

<style type="text/css">

  table,td,th{
      /* border: black 1px solid; */
      border: none;
      border-collapse: collapse;
  }
  body{
      font-family: Arial, Helvetica, sans-serif;
      font-size: 10px;;
  }

  .title{
      font-weight: bold;
      font-size: 12px;
  }
  .bold{
      font-weight: bold;
  }


  .debit-item{
      padding-top: 20px;
      padding-bottom: 200px;
  }


  .total{
      border-top: #000000 2px solid;
  }
  

  .pr-35{
      padding-right: 35px;
  }

 

  .t-head{
      padding-top: 20px;
      text-align: center;
      border-bottom: #000000 2px solid;
  }
 

</style>

<body>
    
    <table width="100%" style="margin-top: 40px">
    
      <tr>
        
        <td  class="logo">
          <img src="https://alumnos.unisierra.edu.mx/img/temple/unisierra.png" alt="logo" width="100" height="90">
        </td>       

      <td colspan="11">
          <span class="title">Universidad de la Sierra</span> <br>
          Carr. Moctezuma - Cumpas, Km. 2.5 <br>
          Moctezuma, Sonora C.P 84560 <br>
          RFC: USI020415U24 <br>
          Tel / Fax : (634) 34296 00
      </td>    

    </tr>

    <tr>
      
      <td style="width: 80px;">
        &nbsp;
      </td>

      <td style="width: 100px;">
      &nbsp;
      </td>

      <td>
        &nbsp;
      </td>

      <td>
        &nbsp;
      </td>

      <td>
        &nbsp;
      </td>

      <td>
        &nbsp;
      </td>

      <td>
        &nbsp;
      </td>

      <td>
        &nbsp;
      </td>

      <td>
        &nbsp;
      </td>

      <td>
        &nbsp;
      </td>

      <td style="width: 90px; text-align: right;">
          Recibo Oficial : <br>
          Fecha :
      </td>

      <td style="width: 80px; text-align: left;">
        <span class="bold">{{ $ticketInfo["ticketNum"]}}</span><br>
        {{ $ticketInfo["date"]}}
      </td>

    </tr>

    <tr>
      
      <td style="width: 80px;">
          Matricula: <br>
          Nombre: <br>
          Dirección: <br>
          Carrera: <br>
          Referencia: 
      </td>

      <td colspan="7">
          {{ $ticketInfo["enrollment"]}} <br>
          {{ ucwords($ticketInfo["name"])}}<br>
          {{ ucwords($ticketInfo["location"])}}<br>
          {{ ucwords($ticketInfo["career"])}}<br>
          {{ $ticketInfo["order"]}}
      </td>

      <td style="width: 80px;">
        RFC : <br>
        <br>
        Semestre :
      </td>

      <td  style="width: 80px;">
          {{ $ticketInfo["rfc"]}}<br>
          <br>
          {{ $ticketInfo["semester"]}}
      </td>
      <td colspan="2" style="text-align: center;">
          &nbsp; <br>
          <br>
          Gpo :  {{ $ticketInfo["group"]}} 
      </td>

    </tr>


    <tr>
        <td class="t-head" colspan="2">Periodo</td>
        <td class="t-head" colspan="8">Concepto</td>
        <td class="t-head" colspan="2">Importe Recibido</td>
    </tr>


    <tr>
      <td class="debit-item" style="text-align: center;" colspan="2">
          {{ $ticketInfo["period"]}}
      </td>

      <td class="debit-item" style="text-align: center;" colspan="8">
          {{ $ticketInfo["concept"]}}
      </td>

      <td class="debit-item pr-35" style="text-align: right;" colspan="2">
          {{ $ticketInfo["amount"]}}
      </td>

    </tr>

    <tr>
      <td style="text-align: center;" colspan="2">&nbsp;</td>

      <td style="text-align: right" colspan="8">
          <span class="bold">Total :</span>
      </td>

      <td class="total pr-35" style="text-align: right;"colspan="2">
          <span class="bold">$ {{ $ticketInfo["amount"]}}</span>
      </td>

    </tr>


    <tr>
        <td colspan="8">&nbsp;</td>
        <td colspan="4">( Son : {{ ucwords($ticketInfo["strAmount"])}} Pesos 00/100 M.N.)</td>
    </tr>

    <tr>
        <td colspan="1">Forma de Pago :</td>
        <td colspan="11">{{ $ticketInfo["payment_method"]}}</td>
    </tr>

    <tr>
      <td colspan="12" style="padding-top: 450px;">
          Cualquier duda con el pago del adeudo y obligaciones contraidas con la 
          Universidad de la Sierra, comunicarse al Dpto. de Recursos Financieros al
          6343429600 Ext.6
      </td>
      
  </tr>
    
  </table>

 

</body>
</html>

