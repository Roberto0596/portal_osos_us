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
        
            <th>FICHA PARA PAGO DE INSCRIPCIÓN EN BANCO</th>

        </tr>

        <tr>
        
            <td colspan="2" class="info"><b>MONTO DEL PAGO: </b>
                
                <?php

                    $total_pagar = 1950.00;
                    if($deuda_total != 0){
                        $total_pagar += $deuda_total;
                    }

                ?>
            ${{  number_format($total_pagar,2)}}
            </td>
        
        </tr>

        <tr>
        
            <td colspan="2" class="info"><b>BANCO: </b>Santander</td>
        
        </tr>

        <tr>
        
            <td colspan="2" class="info" ><b>REFERENCIA: </b>{{$alumno['Matricula']}}</td>
        
        </tr>

        <tr>
        
            <td colspan="2" class="info" ></td>
        
        </tr>

        <tr>
        
            <td colspan="2" class="subtitle"><b>Depósito en Banco</td>
        
        </tr>

       

        <!-- deposito en banco -->

        <tr>
        
            <td colspan="2" class="info"><b>CUENTA: </b>51908243936</td>
            
        
        </tr>

        <tr>
        
            <td colspan="2" class="info" ></td>
        
        </tr>

      
         <!-- termina deposito en banco -->

         <tr>
        
            <td colspan="2" class="subtitle"><b>Transferencia</td>
        
        </tr>

        
         <!-- transferencia  -->

        <tr>
        
            <td colspan="2" class="info"><b>CUENTA CLABE: </b>014775519082439362</td>
        
        </tr>

         <tr>

             <td colspan="2" class="last" ><b>BENEFICIARIO: </b>Universidad de la Sierra</td>
             

         </tr>

         

         <!--termina transferencia  -->

        <tr>
        
            <th align="center" colspan="2" class="pie_de_formato" style="padding-top: 6%;">ATENTAMENTE</th>

        </tr>

        <tr>
        
            <th align="center" colspan="2" class="pie_de_formato" style="padding-bottom: 6%;">Dpto. de Administración y Finanzas</th>

        </tr>
    
    </table>

</body>
</html>

