<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Pago</title>
</head>

<style type="text/css">

    table,td,th{
        border: none;
        border-collapse: collapse;
        padding-top: 5px;
        padding-bottom: 5px;
        padding-left: 15px;
        padding-right: 15px;
    }

    table{
      border: none;
    }

    body{
      font-family: Arial, Helvetica, sans-serif;
      font-size: 10px;
    }

    .title{
      font-size: 14px;
      font-weight: bold;
    }

    .logo{
      width: 100px;
    }
    .school-info{
      vertical-align: middle;
    }

    .table-head{
      padding-top: 20px;
      text-align: center;
      border-bottom: #000000 2px solid;
      
    }

    .table-cell{
      padding-top: 10px;
      text-align: center;
      padding-bottom: 150px;
    }

    .table-cell-right{
      padding-top: 10px;
      text-align: right;
    }

    .table-cell-border-top{
      border-top: #000000 2px solid;
      padding-top: 10px;
      text-align: center;
    }

    .align-right{
      text-align: right;
    }

    .bold{
      font-weight: bold;
    }

   

</style>

<body>
    
    <table width="100%" style="margin-top: 40px">
    
      <tr>
        
        <td  class="logo">
          <img src="https://alumnos.unisierra.edu.mx/img/temple/unisierra.png" alt="logo" width="100" height="90">
        </td>       

        <td colspan="2" class="school-info">
          <span class="title">Universidad de la Sierra</span> <br>
          Carr. Moctezuma - Cumpas, Km. 2.5 <br>
          Moctezuma, Sonora C.P 84560 <br>
          RFC: USI020415U24 <br>
          Tel / Fax : (634) 34296 00
        </td>

        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>

      </tr>

      <tr>
          
        <td> &nbsp;</td>

        <td> &nbsp;</td>

        <td>&nbsp;</td>

        <td class="align-right">
          Recibo Oficial : <br>
          Fecha :
        </td>
        <td style="padding-left: 5px;">
          <span class="bold">{{ $ticketInfo["ticketNum"]}}</span>  <br>
          {{ $ticketInfo["date"]}}
        </td>

      </tr>


      <tr>
          
        <td  style="width: 20px;" >
          Matricula: <br>
          Nombre: <br>
          Dirección: <br>
          Carrera: <br>
          <br>
          Referencia: 
        </td>

        <td >
            {{ $ticketInfo["enrollment"]}} <br>
            {{ ucwords($ticketInfo["name"])}}<br>
            {{ ucwords($ticketInfo["location"])}}<br>
            {{ ucwords($ticketInfo["career"])}}<br>
            {{ $ticketInfo["order"]}}
        </td>

        <td >
         RFC: <br>
         &nbsp; <br>
         Semestre: <br>
        </td>

        <td >
            {{ $ticketInfo["rfc"]}} <br>
          &nbsp; <br>
          {{ $ticketInfo["semester"]}}<br>
        </td>

        <td >
          &nbsp; <br>
          &nbsp; <br>
          Gpo: {{ $ticketInfo["group"]}} <br>
        </td>

      </tr>

      <tr>
          
        <td  class="table-head">
          Periodo
        </td>

        <td colspan="2" class="table-head">
          Concepto
        </td>

        <td colspan="10" class="table-head" style="text-align:right">
          Importe Recibido
        </td>

      </tr>

      <tr>
          
        <td  class="table-cell">
            {{ $ticketInfo["period"]}}
        </td>

        <td colspan="2" class="table-cell">
            {{ $ticketInfo["concept"]}}
        </td>

        <td colspan="10" class="table-cell" style="text-align:right">
          $ &nbsp;&nbsp;&nbsp;{{ $ticketInfo["amount"]}}
        </td>

      </tr>

      <tr >
          
        <td  >
          &nbsp;
        </td>

        <td colspan="2" class="table-cell-right">
          &nbsp;<span class="bold">Total:</span>
        </td>

        <td colspan="10" class="table-cell-border-top" style="text-align:right">
          <span class="bold">$ &nbsp;&nbsp;&nbsp;{{ $ticketInfo["amount"]}}</span>
          
        </td>

      </tr>


      <tr>
          
        <td colspan="2">
          &nbsp;
        </td>
        <td colspan="3" style="text-align: end;">
          (Son : {{ ucwords($ticketInfo["strAmount"])}} pesos 00/100 M.N.)
        </td>

      </tr>

      <tr>
          
        <td  style="width: 100px;">
          Forma de Pago: <br>
        </td>

        <td>
            {{ $ticketInfo["payment_method"]}} <br>
        </td>

        <td >
         &nbsp; 
        </td>

        <td >
          &nbsp; 
        </td>

        <td >
          &nbsp; <br>
        </td>

      </tr>

      <tr>
        <td colspan="12" style="padding-top: 350px; padding-bottom: 50px;">
          Cualquier duda con el pago del adeudo y obligaciones contraidas con la 
          Universidad de la Sierra, comunicarse al Dpto. de Recursos Financieros al
          6343429600 Ext.6
        </td>
      </tr>
      
    </table>

</body>
</html>

