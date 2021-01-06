<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago en Banco</title>
</head>

<style type="text/css">

    table,td,th{
        border: 1px solid black;
        border-collapse: collapse;
    }
    .logo{
        border: none;
        border-bottom: 1px solid black
    }
    .titulo1{
        border-right: none;
        border-bottom: none;
        border-top: none;
    }
    .titulo2{
        border-left: none;
    }
    .info{
       border:none;
       padding-top: 2.6%;
       padding-left: 3%;
       font-size: 13px;
       align: left;
    }
    .pie_de_formato{
        border: none;
        padding: 2%;
    }
    .subtitle{
       padding-left: 3%;
       font-size: 10px;
       align: center;
    }
    .last{
       border:none;
       padding-top: 2.6%;
       padding-bottom: 2.6%;
       padding-left: 3%;
       font-size: 13px;
       align: left;
       border-bottom: 1px solid black

    }

</style>

<body>
    
    <table width="80%">
    
        <tr>
        
            <td rowspan="2"  class="logo">
                <img src="{{ asset('img/logo.jpg') }}" alt="" width="60" height="60" >
            </td>
            <th class='titulo1' width="100%">UNIVERSIDAD DE LA SIERRA</th>

        </tr>

        <tr>
        
            <th>Ticket</th>

        </tr>


         <tr>
        
            <td colspan="2" class="info"><b>ALUMNO: </b>
           {{  ucwords($alumn->name)." ".ucwords($alumn->lastname) }}
            </td>
        
        </tr>

          <tr>
        
            <td colspan="2" class="info"><b>CONCEPTO: </b>
           {{ $debit->description}}
            </td>
        
        </tr>

        <tr>
        
            <td colspan="2" class="info"><b>MONTO: </b>
           {{ "$".number_format($debit->amount,2) }}
            </td>
        
        </tr>


        <tr>
        
            <td colspan="2" class="info" ><b>FECHA: </b>{{$date}}</td>
        
        </tr>

        <tr>
        
            <td colspan="2" class="info" ></td>
        
        </tr>

     
    
    </table>

</body>
</html>

