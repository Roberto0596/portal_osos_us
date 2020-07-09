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

</style>

<body>
    
    <table width="80%">
    
        <tr>
        
            <td rowspan="2"  class="logo">
                <img src="{{ asset('img/logo.jpg') }}" alt="" width="60" height="60" >
            </td>
            <th class='titulo1' width="100%"><?php
                dd(localtime(time(),true));
            ?>
            </th>

        </tr>

        <tr>
        
            <th>FICHA PARA PAGO DE INSCRIPCIÓN EN BANCO</th>

        </tr>

        <tr>
        
            <td colspan="2" class="info"><b>BENEFICIARIO: </b>Universidad de la Sierra</td>
        
        </tr>

        <tr>
        
            <td colspan="2" class="info"><b>MONTO DEL PAGO: </b>
            
                <?php

                    $total_pagar = 1950.00;
                    if($deuda_total != 0){
                        $total_pagar += $deuda_total;
                    }

                ?>
            $ {{$total_pagar}}
            </td>
        
        </tr>

        <tr>
        
            <td colspan="2" class="info"><b>BANCO: </b>Santander</td>
        
        </tr>

        <tr>
        
            <td colspan="2" class="info"><b>CUENTA CLABE: </b>014775519082439362</td>
        
        </tr>

        <tr>
        
            <td colspan="2" class="info" ><b>REFERENCIA: </b>{{$alumno['Matricula']}}</td>
        
        </tr>

        <tr>
        
            <th align="center" colspan="2" class="pie_de_formato" style="padding-top: 13%;">ATENTAMENTE</th>

        </tr>

        <tr>
        
            <th align="center" colspan="2" class="pie_de_formato" style="padding-bottom: 6%;">Dpto. de Administración y Finanzas</th>

        </tr>
    
    </table>

</body>
</html>

