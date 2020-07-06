<!DOCTYPE html>
<html>
<head>
    <title>CEDULA</title>
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
</head>

<?php
    function tipoSangre($tipoSangre){
        if($tipoSangre == 0){
            return 'A+';
        }
        elseif($tipoSangre == 1){
            return 'A-';
        }
        elseif($tipoSangre == 2){
            return 'B+';
        }
        elseif($tipoSangre == 3){
            return 'B-';
        }
        elseif($tipoSangre == 4){
            return 'O+';
        }
        elseif($tipoSangre == 5){
            return 'O-';
        }
        elseif($tipoSangre == 6){
            return 'AB+';
        }
        elseif($tipoSangre == 7){
            return 'AB-';
        }else{
            return 'S/D';
        }
    }
?>

<body>
    <div style="padding-top:50vh" class="digital">

        <table> <!-- Tabla del titulo -->

            <tr >

                <td rowspan="2" width="25%">
                <img id="logo" src="{{ asset('img/logo.jpg') }}" alt="" width="100" height="100">
                </td>
                <th width="120%" style="font-size: 21px"> UNIVERSIDAD DE LA SIERRA </th>
                <th width="25%" style="font-size: 13px"> 
                    CÓDIGO:<br>
                    66-SEE-P01-FO2/REV.01
                </th>

            </tr>

            <tr>
                <th style="font-size: 21px;border-top: none;"> CÉDULA DE REINSCRIPCIÓN </th>
                <th style="font-size: 13px;border-right: none;border-top: none;">HOJA: 1 de 1</th>
            </tr>

        </table>

        <table  width="120%" style="margin-top: 1.5%;border-bottom: none;"> 
            <!-- Tabla de datos personales -->
            <tr >

                <th colspan="4" align="left" style="font-size: 17px;">1. DATOS PERSONALES:</th>
            </tr>

            <tr>

                <td  style="border-right: none;font-size: 13px;">NOMBRE</td>
                <td  style="border-right: none;border-left: none;font-size: 13px;">{{$alumno['ApellidoPrimero']}}</td>
                <td  style="border-right: none;border-left: none;font-size: 13px;">{{$alumno['ApellidoSegundo']}}</td>
                <td  style="border-left: none;font-size: 13px;" >{{$alumno['Nombre']}}</td>
            </tr>

            <tr >

                <td style="border-right: none; font-size: 10px;padding-top: 0px;padding-bottom: 0px;border-bottom: none;"></td>
                <td style="border-left: none; border-right: none; font-size: 10px;padding-top: 0px;padding-bottom: 0px;border-bottom: none;">PRIMER APELLIDO</td>
                <td style="border-right: none; border-left: none; font-size: 10px;padding-top: 0px;padding-bottom: 0px;border-bottom: none;">SEGUNDO APELLIDO</td>
                <td style="border-left: none;font-size: 10px;padding-top: 0px;padding-bottom: 0px;border-bottom: none;">NOMBRE</td>
            </tr>

        </table>

        <table  width="120%" style="border-bottom: none">
            <!-- Tabla de datos personales -->
            <tr>
                <td align="left" style="border-right: none;font-size: 13px;border-top: none;border-bottom: none;">DOMICILIO:</td>
                <td style="border-left:  none; border-right: none;font-size: 13px;border-top: none;border-bottom: none;">{{$direccion}}</td>
                <td style="border-right: none; border-left: none;font-size: 13px;border-top: none;border-bottom: none;">{{$alumno['Telefono']}}</td>
            </tr>

            <tr >

                <td   style="border-right: none;font-size: 10px;padding-top: 0px;padding-bottom: 0px;border-bottom: none;"></td>
                <td  style="border-right: none; border-left: none; font-size: 10px;padding-top: 0px;padding-bottom: 0px;border-bottom: none;">CALLE,NÚMERO,COLONIA,LOCALIDAD,MUNICIPIO,ESTADO Y CÓDIGO POSTAL</td>
                <td style="border-left: none;font-size: 10px;padding-top: 0px;padding-bottom: 0px;border-bottom: none;">TELÉFONO</td>
            </tr>

        </table>

        <table  width="120%">
            <!-- Tabla de datos personales -->
            <tr>
                <td style="border-right: none" align="left;font-size: 13px;">LUGAR DE NAC:</td>
                <td style="border-left:  none;border-right: none;font-size: 13px; padding-left: 0px">{{$lugar_nacimiento['municipio']}}, {{$lugar_nacimiento['estado']}}</td>
                <td style="border-left:  none;border-right: none;font-size: 13px;">EDO. CIVIL:</td>
                <td style="border-left:  none;border-right: none;font-size: 13px;">
                
                <?php 
                    $edocivil = 'SOLTERO';
                    if($alumno['EdoCivil'] == 1){
                        $edocivil = 'CASADO';
                    }
                    
                ?>
                    {{$edocivil}}

                </td>

                <td colspan="2" style="border-left:  none;border-right: none;font-size: 13px;" >CURP :</td>
                <td colspan="2" style="border-left:  none;border-right: none;font-size: 13px; padding-left: 0px">{{$alumno['Curp']}}</td>
            </tr>

        </table>

        <table  width="120%" style="border-top: none;">
            <!-- Tabla de datos personales -->
            <tr>

                <td style="border-right: none; width: 20%;font-size: 13px;border-top: none;"   >FECHA DE NAC:</td>
                <td style="border-left:  none; border-right: none;font-size: 13px;border-top: none;">
                    <?php
                        //método para colocar la fecha de nacimiento.
                        setlocale(LC_TIME, "spanish");
                        $born_mounth = substr($alumno['FechaNacimiento'], 5,2);
                        $born_mounth = DateTime::createFromFormat('!m', $born_mounth);
                        $born_mounth = strftime("%B", $born_mounth->getTimestamp());
                        $fecha_nacimiento = substr($alumno['FechaNacimiento'],8,2).'/'.strtoupper($born_mounth).'/'.substr($alumno['FechaNacimiento'],0,4);
                    ?>
                    {{$fecha_nacimiento}}

                </td>

                <td  style="border-left:  none; border-right: none;font-size: 13px;border-top: none;">GÉNERO:</td>
                <td  style="border-left:  none; border-right: none;font-size: 13px;border-top: none;">
                    <?php 
                    
                        if ($alumno['Genero'] == 0){
                            $genero = 'MASCULINO';
                        }
                        else{
                            $genero = 'FEMENINO';
                        }

                    ?>
                    {{$genero}}

                </td>

                <td  style="border-right: none; border-left: none;font-size: 13px;border-top: none;">E-MAIL:</td>
                <td colspan="2" align="left" style="border-right: none; border-left: none;font-size: 13px;border-top: none;">{{$alumno['Email']}}</td>
            </tr>

        </table>

        <table id="datos2" width="120%" style="border-top: none;">
            <!-- Tabla de datos personales -->
            <tr>
                <td style="border-right: none;font-size: 13px;border-top: none;">TIPO DE SANGRE: &nbsp;&nbsp;&nbsp;
                    {{tipoSangre($alumno['TipoSangre'])}}
                </td>               
                <td style="border-left:  none; border-right: none;font-size: 13px;border-top: none;">ALERGIAS:</td>
                <td style="border-left:  none; border-right: none;font-size: 13px;border-top: none;">{{$alumno['Alergias']}}</td>
                <td style="border-right: none; border-left: none;font-size: 13px;border-top: none;">PADECIMIENTOS:</td>
                <td style="border-left:  none; border-right: none;font-size: 13px;border-top: none;">{{$alumno['Padecimiento']}}</td>
            </tr>

            <tr>
                <td style="border-right: none;font-size: 13px;border-top: none;">SERVICIO MÉDICO:</td>
                <td align=" center" style="border-left:  none; border-right: none;font-size: 13px;border-top: none;">IMSS</td>
                <td align="left"  align="right" style="border-left:  none; border-right: none;font-size: 13px;border-top: none;">NO.AFILIACIÓN</td>
                <td align="right" style="border-right: none; border-left: none;font-size: 13px;border-top: none;">{{$alumno['NumAfiliacion']}}</td>
            </tr>

        </table>

        <table  width="120%" style="border-top: none; " >
            <tr>
                <td style="border-right: none; border-top: none;width: 22%;padding-top: 1px;padding-bottom: 1px;font-size: 15px;">CONTACTO EN CASO <br> DE EMERGENCIA:</td>
                <td style="border-left:  none; border-right: none; border-top: none; width: 30%;padding-top: 1px;padding-bottom: 1px">{{$alumno['ContactoEmergencia']}}</td>
                <td style="border-left:  none; border-right: none; border-top: none;padding-top: 1px;padding-bottom: 1px">DOMICILIO:</td>
                <td style="border-top: none;border-left:  none; border-right: none;width: 30%;padding-top: 1px;padding-bottom: 1px">{{$alumno['ContactoDomicilio']}}</td>
                <td style="border-left:  none; border-right: none; border-top: none;padding-top: 1px;padding-bottom: 1px" align="left">TELÉFONO: </td>
                <td style="border-left:  none; border-right: none; border-top: none;padding-top: 1px;padding-bottom: 1px" align="left">{{$alumno['ContactoTelefono']}}</td>
            </tr>
        </table>

        <table id="espacio" width="120%" >
            <!-- Tabla de datos escolares -->
            <tr >
                <th colspan="8" align="left" style="font-size: 17px;border-bottom: none;">2. DATOS ESCOLARES:</th>
            </tr>
            <tr>
                <td  colspan="2" style="border-right: none;border-bottom: none; ">MATRICULA</td>
                <td colspan="1" style="border-left:  none; border-right: none;border-bottom: none;  ">{{$alumno['Matricula']}}</td>
                <td  style="border-left:  none; border-right: none;border-bottom: none; ">CARRERA: </td>
                <td  colspan="4" style="border-left:  none; border-right: none;border-bottom: none; ">{{$datos_escolares['carrera']['carrera']}}</td>
            </tr>
            <tr>
                <td  style="border-right: none; width: 15%">PLAN:</td>
                <td  style="border-left:  none; border-right: none; width: 15%">{{$datos_escolares['carrera']['planDeEstudio']}}</td>
                <td  style="border-left:  none; border-right: none; width: 15%">PERIODO:</td>
                <td  style="border-right: none; border-left: none;border-right: none; width: 15%">{{$datos_escolares['periodo']}}</td>
                <td  style="border-right: none;border-left: none;width: 15%">SEMESTRE</td>
                <td  style="border-left:  none; border-right: none; width: 15%">{{$datos_escolares['semestre']}}</td>
                <td  style="border-left:  none; border-right: none; width: 15%">GRUPO:</td>
                <td  style="border-right: none; border-left: none; width: 15%">ISC 2-1</td>
            </tr>
            <tr >
                <td colspan="2" style="border-right: none;border-top: none; padding: auto; font-size: 17px">ESCUELA DE PROC:</td>
                <td colspan="2" style="border-left:  none; border-right: none;border-top: none;font-size: 17px">{{$datos_escolares['escuela_procedencia']}}</td>
                <td  style="border-left:  none; border-right: none;border-top: none;font-size: 17px">AÑO EGRESO:</td>
                <td style="border-left:  none; border-right: none;border-top: none;font-size: 17px;  padding-left: none">{{$alumno['AnioEgreso']}}</td>
                <td style="border-left:  none; border-right: none;border-top: none;font-size: 17px; padding-left: none">PROMEDIO:</td>
                <td style="border-right: none; border-left: none;border-top: none;font-size: 17px">{{$alumno['PromedioBachiller']}}</td>
            </tr>
        </table>

        <table id="espacio" width="120%">
            <!-- Tabla de datos familiares -->
            <tr >
                <th colspan="6" align="left" style="font-size: 17px;border-bottom: none;">3. DATOS FAMILIARES:</th>
            </tr>
            <tr style="padding-top: 0px;padding-bottom: 0px">
                <td colspan="6" style="border-right: none; font-size: 10px;padding-top: 0px;padding-bottom: 0px;border-bottom: none;">DATOS DEL PADRE O TUTOR:</td>   
            </tr>
            <tr>
                <td style="border-right: none;font-size: 13px;border-bottom: none;">NOMBRE:</td>
                <td style="border-left:  none; border-right: none;font-size: 13px;border-bottom: none;">{{$alumno['TutorNombre']}}</td>
                <td style="border-left:  none; border-right: none;font-size: 13px;border-bottom: none;">DIRECCIÓN:</td>
                <td style="border-left:  none; border-right: none;font-size: 13px;border-bottom: none;">{{$alumno['TutorDomicilio']}}</td>
                <td style="border-left:  none; border-right: none;font-size: 13px;border-bottom: none;">TELÉFONO:</td>
                <td style="border-right: none; border-left: none;font-size: 13px;border-bottom: none;">{{$alumno['TutorTelefono']}}</td>
            </tr>
            <tr>
                <td colspan="1" style="border-right: none;font-size: 13px;border-bottom: none;">OCUPACIÓN:</td>
                <td colspan="1" style="border-left:  none; border-right: none;font-size: 13px;border-bottom: none;">{{$alumno['TutorOcupacion']}}</td>
                <td colspan="2" style="border-left:  none; border-right: none;font-size: 13px;border-bottom: none;">SUELDO MENSUAL:</td>
                <td colspan="2" style="border-left:  none; border-right: none;font-size: 13px;border-bottom: none;">{{$alumno['TutorSueldoMensual']}}</td>
            </tr>
            <tr style="padding-top: 0px;padding-bottom: 0px">
                <td colspan="6" style="border-right: none; font-size: 10px;padding-top: 0px;padding-bottom: 0px">DATOS DE LA MADRE:</td>   
            </tr>
            <tr>
                <td style="border-right: none;font-size: 13px;border-top: none;">NOMBRE:</td>
                <td style="border-left:  none; border-right: none;font-size: 13px;border-top: none;">{{$alumno['MadreNombre']}}</td>
                <td style="border-left:  none; border-right: none;font-size: 13px;border-top: none;">DIRECCIÓN:</td>
                <td style="border-left:  none; border-right: none;font-size: 13px;border-top: none;">{{$alumno['MadreDomicilio']}}</td>
                <td style="border-left:  none; border-right: none;font-size: 13px;border-top: none;">TELÉFONO:</td>
                <td style="border-right: none; border-left: none;font-size: 13px;border-top: none;">{{$alumno['MadreTelefono']}}</td>
            </tr>
        </table>

        <table id="espacio" width="120%">
            <!-- Tabla de datos generales -->
            <tr >
                <th colspan="7" align="left" style="font-size: 17px;">4. DATOS GENERALES:</th>
            </tr>
            <tr>
                <td style="border-right: none;font-size: 13px;border-top: none;border-bottom: none;">TRABAJAS ACTUALMENTE</td>
                <td style="border-left:  none; border-right: none;font-size: 13px;border-top: none;border-bottom: none;">
                
                    <?php
                        $trabaja = 'NO';
                        if($alumno['TrabajaActualmente'] == 1){
                            $trabaja = 'SI';
                        }

                    ?>
                    {{$trabaja}}
                </td>
                <td style="border-left:  none; border-right: none;font-size: 13px;border-top: none;border-bottom: none;">LUGAR Y PUESTO QUE DESEMPEÑA:</td>
                <td style="border-left:  none; border-right: none;font-size: 13px;border-top: none;border-bottom: none;">
                
                
                    {{$alumno['Puesto']}}

                </td>
                <td style="border-right: none; border-left: none;font-size: 13px;border-top: none;border-bottom: none;">SUELDO MENSUAL:</td>
                <td style="border-left:  none; border-right: none;font-size: 13px;border-top: none;border-bottom: none;">
                
                
                    {{$alumno['SueldoMensualAlumno']}}

                </td>
            </tr>
            <tr>
                <td colspan="2" style="border-right: none;font-size: 13px;border-bottom: none;">¿UTILIZA EL TRANSPORTE UNISIERRA?</td>
                <td colspan="1" style="border-left:  none; border-right: none;font-size: 13px;border-bottom: none;">MOCTEZUMA</td>
                <td colspan="1" style="border-left:  none; border-right: none;font-size: 13px;border-bottom: none;">
                
                    <?php 
                        
                        $transporte = 'NO';
                        if($alumno['Transporte'] == 0){
                            $transporte = 'SI';
                        }
                    
                    ?>
                    {{$transporte}}
                </td>
                <td colspan="2" style="border-right: none; border-left: none;font-size: 13px;border-bottom: none;">CUMPAS</td>
                <td colspan="1" style="border-right: none; border-left: none;font-size: 13px;border-bottom: none;">
                
                    <?php 
                        
                        $transporte = 'NO';
                        if($alumno['Transporte'] == 1){
                             $transporte = 'SI';
                        }
                        
                    ?>
                    {{$transporte}}
                
                </td>
            </tr>
            <tr>
                <td colspan="3" style="border-right: none;font-size: 13px;">PRACTICAS ALGUNA ACTIVIDAD DEPORTIVA O CULTURAL</td>
                <td colspan="4" style="border-left:  none; border-right: none;font-size: 13px;">
                
                    <?php
                    $deporte = $alumno['DeportePractica'];
                        if($deporte == null){
                            $deporte = 'NO';
                        }
                    ?>
                    {{$deporte}}
                </td> 
            </tr>
        </table>

        <table id="espacio" width="120%">
            <!-- Tabla de protesta -->
            <tr >
                <th colspan="5" align="left" style="font-size: 17px;">5. PROTESTA DE REGLAMENTO:</th>
            </tr>
            <tr>
                <td style="border-right: none ;font-size: 13px;border-top: none;" align="center">
                    PROTESTO CUMPLIR CON LAS DISPOSICIONES ESTABLECIDAS EN EL MARCO NORMATIVO VIGENTE,<br>
                    QUE RIGE LAS ACTIVIDADES DE LA UNIVERSIDAD DE LA SIERRA, ASÍ COMO CON LAS ACTIVIDADES <br>
                    ACADÉMICAS QUE, CON MOTIVO DEL DESAROLLO DEL QUEHACER ACADÉMICO SE ME ASIGNEN.
                </td>
            </tr>
        </table>

        <div  style="margin-top:15%">
            <FONT SIZE=3>
                NOMBRE:__________________________________________&nbsp;&nbsp;&nbsp;&nbsp;FIRMA:_________________________________________________
            </FONT>
        </div>    
        <div  style="margin-top:7%" align="center">
            <FONT SIZE=3>
                MOCTEZUMA, SONORA, MÉXICO A
                <?php
                    //método para obtener la fecha actual.
                    setlocale(LC_TIME, "spanish");
                    $fecha_hoy = strftime("%d de %B de %Y");
                ?> 
                {{strtoupper($fecha_hoy)}}
                </FONT>
        </div>
    </div>
</body>
</html>