<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONSTANCIA</title>
</head>
<style type="text/css">
    body{
        font-size: 13px;
        font-family: "Arial";
    }
    table{
        border-collapse: collapse;
    }
    td{
        padding: 6px 5px;
        font-size: 15px;
    }
    table, th, td {   
            border: 1px solid black;
            border-collapse: collapse;
            text-align: center
        }

        #espacio{
                margin-top: 1.5%;
        }
       
   
</style>

<link rel="stylesheet" href="{{asset("css/custom.css")}}">

<body>

<table>
    <tr >
        <td rowspan="2" width="25%" align="center">
            <img id="logo" src="{{ asset('img/logo.jpg') }}" alt="" width="100" height="100">
        </td>
        <th width="120%" style="font-size: 21px"> UNIVERSIDAD DE LA SIERRA </th>
        <th width="25%" style="font-size: 12px">   
            66-SEE-P01-FO3/REV.01
        </th>

    <tr>
        <th style="font-size: 21px;border-top: none;"> CONSTANCIA DE NO ADEUDO </th>
        <th style="font-size: 19px;">HOJA: 1 de 1</th>
    </tr>
</table>




<div class="tabla-div">

    <table class="tabla-fecha">

        <tr >
            <td colspan="3" style="padding-top: 1px; padding-bottom: 1px; letter-spacing: 30%;
            font-size: 13px">FECHA</td>
        </tr>

        <tr>
            <?php
                setlocale(LC_TIME, "spanish");
                $hoy = getdate();
            ?>
            <td class="celdas-fecha">dd</td>
            <td class="celdas-fecha">mm</td>
            <td class="celdas-fecha" style="width:32%">aaaa</td>
        </tr>

        <tr>
            <td class="celdas-vacias">{{$hoy['mday']}}</td>
            <td class="celdas-vacias">{{$hoy['mon']}}</td>
            <td class="celdas-vacias">{{$hoy['year']}}</td>
        </tr>

    </table>

    <p id="texto-constancia">
        Nombre del alumno: <b>{{$alumno['Nombre']}} {{$alumno['ApellidoPrimero']}} {{$alumno['ApellidoSegundo']}}</b>
        No tiene adeudo alguno, por concepto de préstamo de libros, equipo prestado ó dañado, 
        impresiones, cuotas escolares etc,. a la fecha, po lo que puede proceder a efectuar el trámite
        correspondiente.
    </p>

    <table id="tabla-de-tablas-firma">
        <tr style="border: none !important; margin; none">
            <td style="border: none !important;">
                <table class="tabla-firmas">
                    <tr>
                        <td class="texto-enunciado-firma">ATENTAMENTE <br>
                        <b class="texto-firmas">BIBLIOTECA</b></td>
                    </tr>

                    <tr>
                        <td class="texto-firmas">Nombre, firma y sello</td>
                    </tr>
                </table>
            </td>
            <td style="border: none !important;">
                <table class="tabla-firmas" >
                    <tr>
                        <td class="texto-enunciado-firma2">ATENTAMENTE <br>
                        <b class="texto-firmas">DEPARTAMENTO DE FINANZAS Y <br> CONTABILIDAD</b></td>
                    </tr>

                    <tr>
                        <td class="texto-firmas">Nombre, firma y sello</td>
                    </tr>
                </table>
            </td>
            <td style="border: none !important;">
                <table class="tabla-firmas">
                    <tr>
                        <td class="texto-enunciado-firma">ATENTAMENTE <br>
                        <b class="texto-firmas">CENTRO DE CÓMPUTO</b></td>
                    </tr>

                    <tr>
                        <td class="texto-firmas">Nombre, firma y sello</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</div>



<table style="margin-top: 6%;">
    <tr >
        <td rowspan="2" width="25%" align="center">
        <img id="logo" src="{{ asset('img/logo.jpg') }}" alt="" width="100" height="100">
        </td>
        <th width="120%" style="font-size: 21px"> UNIVERSIDAD DE LA SIERRA </th>
        <th width="25%" style="font-size: 12px">   
            66-SEE-P01-FO3/REV.01
        </th>

    <tr>
        <th style="font-size: 21px;border-top: none;"> CONSTANCIA DE NO ADEUDO </th>
        <th style="font-size: 19px;">HOJA: 1 de 1</th>
    </tr>
</table>




<div class="tabla-div">

    <table class="tabla-fecha">

        <tr >
            <td colspan="3" style="padding-top: 1px; padding-bottom: 1px; letter-spacing: 30%;
            font-size: 13px">FECHA</td>
        </tr>

        <tr>
            <?php
                setlocale(LC_TIME, "spanish");
                $hoy = getdate();
            ?>
            <td class="celdas-fecha">dd</td>
            <td class="celdas-fecha">mm</td>
            <td class="celdas-fecha" style="width:32%">aaaa</td>
        </tr>

        <tr>
            <td class="celdas-vacias">{{$hoy['mday']}}</td>
            <td class="celdas-vacias">{{$hoy['mon']}}</td>
            <td class="celdas-vacias">{{$hoy['year']}}</td>
        </tr>

    </table>

    <p id="texto-constancia">
        Nombre del alumno: <b>{{$alumno['Nombre']}} {{$alumno['ApellidoPrimero']}} {{$alumno['ApellidoSegundo']}}</b>
        No tiene adeudo alguno, por concepto de préstamo de libros, equipo prestado ó dañado, 
        impresiones, cuotas escolares etc,. a la fecha, po lo que puede proceder a efectuar el trámite
        correspondiente.
    </p>

    <table id="tabla-de-tablas-firma">
        <tr style="border: none !important; margin; none">
            <td style="border: none !important;">
                <table class="tabla-firmas">
                    <tr>
                        <td class="texto-enunciado-firma">ATENTAMENTE <br>
                        <b class="texto-firmas">BIBLIOTECA</b></td>
                    </tr>

                    <tr>
                        <td class="texto-firmas">Nombre, firma y sello</td>
                    </tr>
                </table>
            </td>
            <td style="border: none !important;">
                <table class="tabla-firmas" >
                    <tr>
                        <td class="texto-enunciado-firma2">ATENTAMENTE <br>
                        <b class="texto-firmas">DEPARTAMENTO DE FINANZAS Y <br> CONTABILIDAD</b></td>
                    </tr>

                    <tr>
                        <td class="texto-firmas">Nombre, firma y sello</td>
                    </tr>
                </table>
            </td>
            <td style="border: none !important;">
                <table class="tabla-firmas">
                    <tr>
                        <td class="texto-enunciado-firma">ATENTAMENTE <br>
                        <b class="texto-firmas">CENTRO DE CÓMPUTO</b></td>
                    </tr>

                    <tr>
                        <td class="texto-firmas">Nombre, firma y sello</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</div>

    
</body>
</html>