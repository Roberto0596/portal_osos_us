@extends('Alumn.main')
@section('content-alumn')
<link rel="stylesheet" href="{{ asset('css/form.css') }}">

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h3>Verifica que tu información sea correcta</h3>
            </div>
        </div>
        </div>
    </section>

    <section class="content">
        
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="col-md-12 ">
                        <div class="form-register_header">
                            <ul class="progressbar">
                                <li class="progressbar-option active"> Datos Personales I</li>
                                <li class="progressbar-option"> Datos Personales II</li>
                                <li class="progressbar-option"> Datos Escolares</li>
                                <li class="progressbar-option"> Datos Familiares</li>
                                <li class="progressbar-option"> Datos Generales</li>
                                <li class="progressbar-option"> Protesta de Reglamento</li>
                            </ul>
                        </div>
                        <form class="form-inscription" method="POST" action="#">                 

                            <input  id="token"  type="hidden" value="{{csrf_token()}}"/>                       
                            
                            <!-- step 1  DATOS PERSONALES I -->
                            <div class="row active" id="step-1">
                                <div class="col-md-12">
                                    <div class="row ">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Primer Apellido</label>
                                                <input name="ApellidoPrimero"  id="ApellidoPrimero" fieldname="Primer Apellido" 
                                                type="text" style="text-transform:uppercase" 
                                                value="{{ $data['ApellidoPrimero'] == null ? '' : $data['ApellidoPrimero'] }}"  class="form-control"
                                                isnullable="no" placeholder="Ingrese su primer apellido"  />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Segundo Apellido</label>
                                                <input name="ApellidoSegundo" fieldname="Segundo Apellido" id="ApellidoSegundo" type="text" style="text-transform:uppercase" 
                                                value="{{ $data['ApellidoSegundo'] == null ? '' : $data['ApellidoSegundo'] }}"  class="form-control" 
                                                isnullable="si" placeholder="Ingrese su segundo apellido"  />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Nombre</label>
                                                <input id="Nombre" name="Nombre" fieldname="Nombre"  type="text"
                                                value="{{ $data['Nombre'] == null ? '' : $data['Nombre'] }}"  class="form-control"
                                                style="text-transform:uppercase"  isnullable="no" placeholder="Ingrese su nombre"  />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row ">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Domicilio</label>
                                                    <input id="Domicilio" name="Domicilio" fieldname="Domicilio"  type="text"
                                                    value="{{ $data['Domicilio'] == null ? '' : $data['Domicilio'] }}"  class="form-control"
                                                    style="text-transform:uppercase"  isnullable="si" placeholder="Ingrese su domicilio"  />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Colonia</label>
                                                    <input id="Colonia" name="Colonia" fieldname="Colonia"  type="text"
                                                    value="{{ $data['Colonia'] == null ? '' : $data['Colonia'] }}"  class="form-control" 
                                                    style="text-transform:uppercase" isnullable="no" placeholder="Ingrese su colonia"  />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Localidad</label>
                                                <input id="Localidad" name="Localidad" fieldname="Localidad"  type="text"
                                                value="{{ $data['Localidad'] == null ? '' : $data['Localidad'] }}"  class="form-control"
                                                style="text-transform:uppercase" isnullable="si" placeholder="Ingrese su localidad"  />
                                            </div>
                                        </div>



                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="MunicipioDom" data-alias="municipio" class="control-label">Municipio</label>
                                                <select id="MunicipioDom" fieldname="Municipio" name="MunicipioDom"  isnullable="no" class="form-control select2">
                                                    @if($data["MunicipioDom"] != null)
                                                    @php
                                                        $mpioSelected = selectSicoes("Municipio","MunicipioId",$data["MunicipioDom"])[0]; 
                                                    @endphp                                                    
                                                    <option value="{{$mpioSelected['MunicipioId']}}"> {{$mpioSelected['Nombre']}} </option>
                                                    @else
                                                    <option  disabled="" selected="">Seleccionar</option>
                                                    @endif

                                                    @foreach ($municipios as $mpio)
                                                    <option value="{{$mpio['Clave']}}"> {{$mpio['Nombre']}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>




                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="EstadoDom" data-alias="estado" class="control-label">Estado</label>
                                                <select id="EstadoDom" fieldname="Estado" name="EstadoDom"  isnullable="no" class="form-control select2">
                                                    @if($data["EstadoDom"] != null)
                                                    @php
                                                        $edoSelected = selectSicoes("Estado","EstadoId",$data["EstadoDom"])[0]; 
                                                    @endphp                                                    
                                                    <option value="{{$edoSelected['EstadoId']}}"> {{$edoSelected['Nombre']}} </option>
                                                    @else
                                                    <option  disabled="" selected="">Seleccionar</option>
                                                    @endif

                                                    @foreach ($estados as $edo)
                                                    <option value="{{$edo['Clave']}}"> {{$edo['Nombre']}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Código Postal</label>
                                                <input fieldname="Código Postal" maxlength="5" min="5" type="text"class="form-control" name="CodigoPostal"
                                                value="{{ $data['CodigoPostal'] == null ? '' : $data['CodigoPostal'] }}"  isnullable="no" placeholder="Ej. 84330"  />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Teléfono</label>
                                                <input id="Telefono" fieldname="Teléfono"  maxlength="10" min="10" type="text"  name="Telefono" class="form-control"
                                                value="{{ $data['Telefono'] == null ? '' : $data['Telefono'] }}" isnullable="si" placeholder="Ej. 6558036422"  />
                                            </div>
                                        </div>
                                            
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="municipionac" data-alias="municipionac" class="control-label">Municipio de Nacimiento</label>
                                                <select id="MunicipioNac" fieldname="Municipio de Nacimiento" name="MunicipioNac" isnullable="si" class="form-control select2" >
                                                    @if($data["MunicipioNac"] != null)
                                                    @php
                                                        $mpioSelected = selectSicoes("Municipio","MunicipioId",$data["MunicipioNac"])[0]; 
                                                    @endphp                                                    
                                                    <option value="{{$mpioSelected['MunicipioId']}}"> {{$mpioSelected['Nombre']}} </option>
                                                    @else
                                                    <option  disabled="" selected="">Seleccionar</option>
                                                    @endif

                                                    @foreach ($municipios as $mpio)
                                                    <option value="{{$mpio['Clave']}}"> {{$mpio['Nombre']}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                            
                                        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="estadonac" data-alias="estadonac" class="control-label">Estado de Nacimiento</label>
                                                <select id="EstadoNac" fieldname="Estado de nacimiento" isnullable="si" name="EstadoNac" class="form-control select2" >
                                                    @if($data["EstadoNac"] != null)
                                                    @php
                                                        $edoSelected = selectSicoes("Estado","EstadoId",$data["EstadoNac"])[0]; 
                                                    @endphp                                                    
                                                    <option value="{{$edoSelected['EstadoId']}}"> {{$edoSelected['Nombre']}} </option>
                                                    @else
                                                    <option  disabled="" selected="">Seleccionar</option>
                                                    @endif

                                                    @foreach ($estados as $edo)
                                                    <option value="{{$edo['Clave']}}"> {{$edo['Nombre']}} </option>
                                                    @endforeach
                                                </select>
                                                </div>
                                        </div>
                                            

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="edocivil" data-alias="Edo.Civil" class="control-label">Edo.Civil</label>
                                                <select id="EdoCivil" fieldname="Edo.Civil" isnullable="no"  name="EdoCivil" class="form-control" >
                                                    @if($data["EdoCivil"] != null)
                                                    @php
                                                        $value = $data["EdoCivil"] == '0' ? 'SOLTERO' : 'CASADO';
                                                    @endphp                                                    
                                                    <option disabled value="{{$data["EdoCivil"]}}"> {{$value}} </option>
                                                    @else
                                                    <option  disabled="" selected="">Seleccionar</option>
                                                    @endif
                                                    
                                                    <option value="0">SOLTERO</option>
                                                    <option value="1">CASADO</option>
                                                </select>
                                                </div>
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="step_controls">
                                    <button type="button" class="btn btn-warning button-custom button-next"
                                    data-to_step="2" data-step="1">Siguiente</button>
                                </div>

                            </div>

                            <!-- step 2 DATOS PERSONALES II -->
                            <div class="row disabled" id="step-2">
                                <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Curp</label>
                                            <input id="Curp" name="Curp"   fieldname="Curp" isnullable="si" maxlength="18" min="18" type="text" disabled
                                            value="{{ $data['Curp'] == null ? '' : $data['Curp'] }}"  class="form-control" placeholder="Ingrese su curp"  />
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label"><output></output>Año de Nacimiento</label>
                                            <input fieldname="Año de Nacimiento"  isnullable="no" maxlength="4" minlength="4" type="text"  class="form-control date" disabled
                                            value="{{ $data['FechaNacimiento'] == null ? '' : $data['FechaNacimiento'][0].$data['FechaNacimiento'][1].$data['FechaNacimiento'][2].$data['FechaNacimiento'][3] }}"
                                            placeholder="Ej. 1999"  />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="mesNacimiento" data-alias="mesNacimiento" class="control-label">Mes de Nacimiento</label>
                                            <select disabled fieldname="Mes de nacimiento"id="mesNacimiento" isnullable="no" name="mesNacimiento" class="form-control " >
                                                @if($data['FechaNacimiento'] != null)
                                                    @php
                                                    $month = $data['FechaNacimiento'][5].$data['FechaNacimiento'][6];
                                                        $value = '';
                                                        if ( $month ==  '01' ) {
                                                        $value = 'ENERO';
                                                        }elseif ($month ==  '02') {
                                                            $value = 'FEBRERO';
                                                        }elseif ($month ==  '03') {
                                                            $value = 'MARZO';
                                                        }elseif ($month ==  '04') {
                                                            $value = 'ABRIL';
                                                        }elseif ($month ==  '05') {
                                                            $value = 'MAYO';
                                                        }elseif ($month ==  '06') {
                                                            $value = 'JUNIO';
                                                        } if ( $month ==  '07' ) {
                                                        $value = 'JULIO';
                                                        }elseif ($month ==  '08') {
                                                            $value = 'AGOSTO';
                                                        }elseif ($month ==  '09') {
                                                            $value = 'SEPTIEMBRE';
                                                        }elseif ($month ==  '10') {
                                                            $value = 'OCTUBRE';
                                                        }elseif ($month ==  '11') {
                                                            $value = 'NOVIEMBRE';
                                                        }elseif ($month ==  '12') {
                                                            $value = 'DICIEMBRE';
                                                        }
                                                        
                                                    @endphp
                                                    <option value="{{$month}}"> {{$value}} </option>

                                                    
                                                @else
                                                <option value="" disabled="" selected="">Seleccionar </option>
                                                @endif
                                                <option value="01">ENERO</option>
                                                <option value="02">FEBRERO</option>
                                                <option value="03">MARZO</option>
                                                <option value="04">ABRIL</option>
                                                <option value="05">MAYO</option>
                                                <option value="06">JUNIO</option>
                                                <option value="07">JULIO</option>
                                                <option value="08">AGOSTO</option>
                                                <option value="09">SEPTIEMBRE</option>
                                                <option value="10">OCTUBRE</option>
                                                <option value="11">NOVIEMBRE</option>
                                                <option value="12">DICIEMBRE</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="diaNacimiento" data-alias="diaNacimiento" class="control-label">Dia de Nacimiento</label>
                                            <select disabled fieldname="Día de nacimiento" isnullable="no"  id="diaNacimiento" name="diaNacimiento" class="form-control " >
                                                @if($data['FechaNacimiento'] != null)
                                                @php
                                                $day = $data['FechaNacimiento'][8].$data['FechaNacimiento'][9];
                                                    $value = '';
                                                    if ( $month ==  '01' ) {
                                                    $value = 'ENERO';
                                                    }elseif ($month ==  '02') {
                                                        $value = 'FEBRERO';
                                                    }elseif ($month ==  '03') {
                                                        $value = 'MARZO';
                                                    }elseif ($month ==  '04') {
                                                        $value = 'ABRIL';
                                                    }elseif ($month ==  '05') {
                                                        $value = 'MAYO';
                                                    }elseif ($month ==  '06') {
                                                        $value = 'JUNIO';
                                                    } if ( $month ==  '07' ) {
                                                    $value = 'JULIO';
                                                    }elseif ($month ==  '08') {
                                                        $value = 'AGOSTO';
                                                    }elseif ($month ==  '09') {
                                                        $value = 'SEPTIEMBRE';
                                                    }elseif ($month ==  '10') {
                                                        $value = 'OCTUBRE';
                                                    }elseif ($month ==  '11') {
                                                        $value = 'NOVIEMBRE';
                                                    }elseif ($month ==  '12') {
                                                        $value = 'DICIEMBRE';
                                                    }
                                                    
                                                @endphp
                                                <option value="{{$day}}"  selected> {{$day}} </option>
                                                @else
                                                <option value="" disabled="" selected="">Seleccionar </option>
                                                @endif
                                                <option value="01">01</option>
                                                <option value="02">02</option>
                                                <option value="03">03</option>
                                                <option value="04">04</option>
                                                <option value="05">05</option>
                                                <option value="06">06</option>
                                                <option value="07">07</option>
                                                <option value="08">08</option>
                                                <option value="09">09</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="18">18</option>
                                                <option value="19">19</option>
                                                <option value="20">20</option>
                                                <option value="21">21</option>
                                                <option value="22">22</option>
                                                <option value="23">23</option>
                                                <option value="24">24</option>
                                                <option value="25">25</option>
                                                <option value="26">26</option>
                                                <option value="27">27</option>
                                                <option value="28">28</option>
                                                <option value="29">29</option>
                                                <option value="30">30</option>
                                                <option value="31">31</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="genero" data-alias="genero" class="control-label">Género</label>
                                            <select id="genero" fieldname="Género" isnullable="no"  name="Genero" class="form-control " disabled >
                                                @if($data["Genero"] != null)
                                                @php
                                                    $value = $data["Genero"] == '0' ? 'HOMBRE' : 'MUJER';
                                                @endphp                                                    
                                                <option value="{{$data["Genero"]}}"> {{$value}} </option>
                                                @else
                                                <option  disabled="" selected="">Seleccionar</option>
                                                @endif
                                                
                                                <option value="0">HOMBRE</option>
                                                <option value="1">MUJER</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                                <label class="control-label">Correo Electrónico</label>
                                                <input id="Email" fieldname="Correo Electrónico" isnullable="si" style="text-transform:uppercase"  type="email" name="Email"
                                                value="{{ $data == null ? '' : $data['Email'] }}"   class="form-control" placeholder="Ingrese su correo"  />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="tiposangre" data-alias="tiposangre" class="control-label">Tipo de sangre</label>
                                                <select id="TipoSangre"  fieldname="Tipo de sangre" isnullable="no"  name="TipoSangre" class="form-control " >
                                                    @if($data["TipoSangre"] != null)
                                                    @php
                                                    $TipoSangre = $data["TipoSangre"];
                                                    $value='';
                                                    if($TipoSangre == '0'){
                                                        $value ='A+';
                                                    }elseif($TipoSangre == '1'){
                                                        $value ='A-';
                                                    }elseif ($TipoSangre == '2') {
                                                        $value ='B+';
                                                    }elseif ($TipoSangre == '3') {
                                                        $value ='B-';
                                                    }elseif ($TipoSangre == '4') {
                                                        $value ='O+';
                                                    }elseif ($TipoSangre == '5') {
                                                        $value ='O-';
                                                    }elseif ($TipoSangre == '6') {
                                                        $value ='AB+';
                                                    }else if($TipoSangre == '7'){
                                                        $value ='AB-';
                                                    }else {
                                                        $value = 'SIN DATO';
                                                    }
                                                    @endphp
                                                    <option value="{{$data["TipoSangre"]}}"> {{$value}} </option>
                                                    @else
                                                    <option value="" disabled="" selected="">Seleccionar</option>
                                                    @endif
                                                    <option value="0">A+</option>
                                                    <option value="1">A-</option>
                                                    <option value="2">B+</option>
                                                    <option value="3">B-</option>
                                                    <option value="4">O+</option>
                                                    <option value="5">O-</option>
                                                    <option value="6">AB+</option>
                                                    <option value="7">AB-</option>
                                                    <option value="8">SIN DATO</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" >
                                                <label class="control-label"><output></output>Alergias</label>
                                                <input id="Alergias" fieldname="Alergias" name="Alergias"  isnullable="si" id="descAlergia" style="text-transform:uppercase" maxlength="100"
                                                    type="text" class="form-control" 
                                                value="{{ $data == null ? '' : $data['Alergias'] }}" placeholder="Especifique su alergia"  />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group" >
                                                <label class="control-label"><output></output>Padecimiento</label>
                                                <input fieldname="Padecimiento" name="Padecimiento" isnullable="si" style="text-transform:uppercase"
                                                    id="descPadecimiento" maxlength="100" type="text" class="form-control" 
                                                value="{{ $data == null ? '' : $data['Padecimiento'] }}" placeholder="Especifique su padecimiento"  />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="servicioMedico" data-alias="servicioMedico" class="control-label">Servicio Médico</label>
                                                <select id="ServicioMedico" name="ServicioMedico" class="form-control" id="ServicioMedico" isnullable="si">
                                                    @if($data["ServicioMedico"] != null)
                                                    @php
                                                    $value='';
                                                    $ServicioMedico = $data["ServicioMedico"];

                                                    if($ServicioMedico == '0'){
                                                        $value ='IMSS';
                                                    }elseif($ServicioMedico == '1'){
                                                        $value ='ISSSTE';
                                                    }elseif ($ServicioMedico == '2') {
                                                        $value ='ISSSTESON';
                                                    }elseif ($ServicioMedico == '3') {
                                                        $value ='SEGUROPOPULAR';
                                                    }elseif ($ServicioMedico == '4') {
                                                        $value ='PARTICULAR';
                                                    }elseif ($ServicioMedico == '5') {
                                                        $value ='OTRO';
                                                    }
                                                    @endphp
                                                    <option value="{{$ServicioMedico}}"> {{$value}} </option>
                                                    @else
                                                    <option value="" disabled="" selected="">Seleccionar</option>
                                                    @endif
                                                    <option value="0">IMSS</option>
                                                    <option value="1">ISSSTE</option>
                                                    <option value="2">ISSSTESON</option>
                                                    <option value="3">SEGUROPOPULAR</option>
                                                    <option value="4">PARTICULAR</option>
                                                    <option value="5">OTRO</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">No. Afiliación</label>
                                                <input id="NumAfiliacion" name="NumAfiliacion" fieldname="No. Afiliacón"  isnullable="si" type="text"  class="form-control" 
                                                value="{{ $data['NumAfiliacion'] == null ? '' : $data['NumAfiliacion'] }}" placeholder="Ingrese su No. Afiliación"  />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Contacto en caso de Emergencia</label>
                                                <input id="ContactoEmergencia" name="ContactoEmergencia" fieldname="Contacto de Emergencia"  isnullable="si"
                                                    type="text" style="text-transform:uppercase"
                                                value="{{ $data['ContactoEmergencia'] == null ? '' : $data['ContactoEmergencia'] }}"   class="form-control" placeholder="Ingrese su contacto"  />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Domicilio</label>
                                                <input id="ContactoDomicilio" name="ContactoDomicilio" style="text-transform:uppercase" fieldname="Contacto Domicilio" 
                                                    isnullable="si"  type="text"
                                                value="{{ $data['ContactoDomicilio'] == null ? '' : $data['ContactoDomicilio'] }}"   class="form-control" placeholder="Ingrese su domicilio"  />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Teléfono</label>
                                                <input id="ContactoTelefono" name="ContactoTelefono" fieldname="Contacto Teléfono"  isnullable="si"  
                                                maxlength="10" min="10" type="text"   class="form-control"
                                                value="{{ $data['ContactoTelefono'] == null ? '' : $data['ContactoTelefono'] }}" placeholder="Ej. 6558036422"  />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <div class="step_controls">
                                        <button type="btnAnterior" class="btn btn-warning button-custom button-back"
                                        data-to_step="1" data-step="2">Volver</button> 
                                        <button type="button" class="btn btn-warning button-custom button-next"
                                        data-to_step="3" data-step="2">Siguiente</button>
                                    </div>
                            </div>                         

                            <!-- step 3 DATOS ESCOLARES -->                        
                            <div class="row disabled" id="step-3">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Matricula</label>
                                                <input name="Matricula" fieldname="Matricula"  type="text" disabled isnullable="no"
                                                value="{{ $data['Matricula'] == null ? '' : $data['Matricula'] }}"   class="form-control" placeholder="Ingrese su matricula"  />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="Carrera" class="control-label">Carrera</label>
                                                <select id="Carrera" fieldname="Carrera" name="Carrera" isnullable="si" class="form-control " disabled >
                                                    @if ($data != null)

                                                    @php
                                                        $data_studio = selectSicoes("PlanEstudio","PlanEstudioId",$data["PlanEstudioId"]);
                                                        $data_carrer = selectSicoes("Carrera","CarreraId",$data_studio[0]["CarreraId"]);
                                                    @endphp

                                                    <option value="{{ $data_studio[0]['CarreraId']}}" disabled="" selected="">{{$data_carrer[0]['Nombre']}}</option>
                                                    @else
                                                    <option value="" disabled="" selected="">Seleccionar</option>
                                                    @endif
                                                    
                                                    
                                                </select>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="plan" data-alias="plan" class="control-label">Plan De Estudios</label>
                                                <select id="PlanEstudioId" name="PlanEstudioId" isnullable="si" fieldname="Plan De Estudios" class="form-control " disabled >
                                                    @if ($data != null)
                                                    @php
                                                        $data_studio = selectSicoes("PlanEstudio","PlanEstudioId",$data["PlanEstudioId"]);
                                                    @endphp
                                                    <option value="{{ $data["PlanEstudioId"]}}" disabled="" selected="">{{$data_studio[0]['Nombre']}}</option>
                                                    @else
                                                    <option value="" disabled="" selected="">Seleccionar</option>
                                                    @endif
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="Periodo" data-alias="Periodo" class="control-label">Periodo</label>
                                                <select id="Periodo" name="Periodo" fieldname="Periodo"  class="form-control " disabled >
                                                    @if ($data != null)
                                                    @php
                                                        $period = selectCurrentPeriod();
                                                    @endphp
                                                    <option value="{{ $period["PeriodoId"]}}" disabled="" selected="">{{$period['Clave']}}</option>
                                                    @else
                                                    <option value="" disabled="" selected="">Seleccionar</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="Semestre" data-alias="Semestre" class="control-label">Semestre</label>
                                                <select id="Semestre" fieldname="Semestre"  class="form-control " disabled >
                                                    @if ($data != null)
                                                    @php
                                                        $lastSemester =getLastSemester($currentId) + 1;
                                                    @endphp
                                                    <option disabled="" selected="">{{$lastSemester}}</option>
                                                    @else
                                                    <option value="" disabled="" selected="">Seleccionar</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="Grupo" data-alias="Grupo" class="control-label">Grupo</label>
                                                <input type="text" class="form-control" value="{{$group['Nombre']}}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="escuelaprocedencia" data-alias="escuelaprocedencia" class="control-label">Escuela de Procedencia</label>
                                                <select id="EscuelaProcedenciaId"name="" fieldname="Escuela de Procedencia"  class="form-control " disabled >
                                                    @if ($data != null)
                                                    @php
                                                        
                                                        $school = selectSicoes("Escuela","EscuelaId",$data["EscuelaProcedenciaId"]);

                                                    @endphp
                                                    <option disabled="" selected="">{{$school[0]['Nombre']}}</option>
                                                    @else
                                                    <option value="" disabled="" selected="">Seleccionar</option>
                                                    @endif
                                                    
                                                    
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label"><output></output>Año de Egreso</label>
                                                <input fieldname="Año de Egreso" maxlength="4" minlength="4" type="text"  class="form-control" disabled
                                                value="{{ $data == null ? '' : $data['AnioEgreso'] }}"
                                                placeholder="Ej. 1999"  />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label"><output></output>Promedio</label>
                                                <input fieldname="Promedio" maxlength="4" minlength="4" type="text"  class="form-control" disabled
                                                value="{{ $data == null ? '' : $data['PromedioBachiller'] }}"
                                                placeholder="Ej. 1999"  />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="step_controls">
                                    <button type="button" class="btn btn-warning button-custom button-back"
                                    data-to_step="2" data-step="3">Volver</button> 
                                    <button type="button" class="btn btn-warning button-custom button-next"
                                    data-to_step="4" data-step="3">Siguiente</button>
                                    
                                </div>
                            </div>

                            <!-- step 4 DATOS FAMILIARES -->
                            <div class="row disabled" id="step-4">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Nombre completo del tutor</label>
                                                <input fieldname="Nombre completo del tutor" maxlength="100" type="text"
                                                    name="TutorNombre"  class="form-control" style="text-transform:uppercase"
                                                value="{{ $data == null ? '' : $data['TutorNombre']}}" placeholder="Ingrese el nombre del tutor"  />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Domicilio del tutor</label>
                                                <input fieldname="Domicilio del tutor" maxlength="100" type="text"
                                                    name="TutorDomicilio"   class="form-control" style="text-transform:uppercase"
                                                value="{{ $data == null ? '' : $data['TutorDomicilio']}}" placeholder="Ingrese el domicilio del tutor"  />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Teléfono del tutor</label>
                                                <input fieldname="Teléfono del tutor" maxlength="100" type="text"  name="TutorTelefono" class="form-control"
                                                value="{{ $data == null ? '' : $data['TutorTelefono']}}" placeholder="Ingrese el teléfono del tutor"  />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Ocupación del tutor</label>
                                                <input fieldname="Ocupación del tutor" maxlength="100" type="text"
                                                    name="TutorOcupacion"  class="form-control" style="text-transform:uppercase"
                                                value="{{ $data == null ? '' : $data['TutorOcupacion']}}" placeholder="Ingrese la ocupación del tutor"  />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Sueldo mensual del tutor</label>
                                                <input fieldname="Sueldo Mensual" maxlength="100" type="text" name="TutorSueldoMensual"  class="form-control"
                                                value="{{ $data == null ? '' : $data['TutorSueldoMensual']}}" placeholder="Ingrese el sueldo mensual del tutor"  />
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Nombre completo de la madre</label>
                                                <input fieldname="Nombre completo de la Madre" maxlength="100" type="text"
                                                    name="MadreNombre"   class="form-control" style="text-transform:uppercase"
                                                value="{{ $data == null ? '' : $data['MadreNombre']}}" placeholder="Ingrese el nombre de la madre"  />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Domicilio de la madre</label>
                                                <input fieldname="Domicilio de la madre" maxlength="100" type="text"
                                                style="text-transform:uppercase" name="MadreDomicilio" class="form-control"
                                                value="{{ $data == null ? '' : $data['MadreDomicilio']}}" placeholder="Ingrese el domicilio de la madre"  />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Teléfono de la madre</label>
                                                <input fieldname="Teléfono de la Madre" maxlength="100" type="text"  name="MadreTelefono"  class="form-control"
                                                value="{{ $data == null ? '' : $data['MadreTelefono']}}" placeholder="Ingrese el teléfono de la madre"  />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="step_controls">
                                    <button type="button" class="btn btn-warning button-custom button-back"
                                    data-to_step="3" data-step="4">Volver</button> 
                                    <button type="button" id="btnSiguiente" class="btn btn-warning button-custom button-next"
                                    data-to_step="5" data-step="4">Siguiente</button>
                                </div>
                            </div>

                            <!-- step 5 DATOS GENERALES-->
                            <div class="row disabled" id="step-5">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="trabajaactualmente" data-alias="trabajaactualmente" class="control-label">¿Trabaja Actualmente?</label>
                                                <select  name="TrabajaActualmente" class="form-control" id="trabajaactualmente" isnullable="no" >
                                                    @if($data["TrabajaActualmente"] != null)
                                                    @php
                                                        $value = $data["TrabajaActualmente"] == '0' ? 'NO' : 'SI';
                                                    @endphp                                                    
                                                    <option disabled value="{{$data["TrabajaActualmente"]}}"> {{$value}} </option>
                                                    @else
                                                    <option  disabled="" selected="">Seleccionar</option>
                                                    @endif
                                                    <option value="0">NO</option>
                                                    <option value="1">SI</option>
                                                </select>
                                                </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="control-label">Puesto</label>
                                                <input id="Puesto" name="Puesto" fieldname="Puesto" maxlength="100" type="text" disabled  class="form-control"
                                                value="{{ $data['Puesto'] == null ? '' : $data['Puesto']}}" isnullable="si" placeholder="Ingrese el puesto"  />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            
                                            <div class="form-group">
                                                <label class="control-label">Sueldo mensual del alumno</label>
                                                <input fieldname="Sueldo mensual del alumno" maxlength="100" type="text" disabled id="SueldoMensualAlumno"  
                                                class="form-control" name="SueldoMensualAlumno"
                                                value="{{ $data['SueldoMensualAlumno'] == null ? '' : $data['SueldoMensualAlumno']}}" 
                                                isnullable="si" placeholder="Ingrese el sueldo mensual del alumno"  />
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="transporteuniversidad" data-alias="transporteuniversidad" class="control-label">¿utiliza el transporte unisierra?</label>
                                                <select  name="TransporteUniversidad" class="form-control" id="transporteuniversidad" >
                                                    @if($data["TransporteUniversidad"] != null)
                                                    @php
                                                        $value = $data["TransporteUniversidad"] == '0' ? 'NO' : 'SI';
                                                    @endphp                                                    
                                                    <option  value="{{$data["TransporteUniversidad"]}}"> {{$value}} </option>
                                                    @else
                                                    <option  disabled="" selected="">Seleccionar</option>
                                                    @endif
                                                    <option value="0">No</option>
                                                    <option value="1">Si</option>
                                                </select>
                                                </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="transporte" data-alias="transporte"  class="control-label">¿Cual?</label>
                                                <select  name="Transporte" class="form-control"   id="transporte" >
                                                    @if($data["TransporteUniversidad"] != null)
                                                    @php
                                                        $value = $data["Transporte"] == '0' ? 'MOCTEZUMA' : 'CUMPAS';
                                                    @endphp                                                    
                                                    <option  disabled value="{{$data["Transporte"]}}"> {{$value}} </option>
                                                    @else
                                                    <option  disabled="" selected="">SELECCIONAR</option>
                                                    @endif
                                                    <option value="0">MOCTEZUMA</option>
                                                    <option value="1">CUMPAS</option>
                                                </select>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="control-label">Practicas alguna actividad deportiva o cultural</label>
                                                <input fieldname="Actividad cultural" maxlength="100" type="text"  isnullable="si" 
                                                class="form-control" name="DeportePractica" style="text-transform:uppercase"
                                                value="{{ $data['DeportePractica'] == null ? '' : $data['DeportePractica']}}" placeholder="ej.Danza"  />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="step_controls">
                                    <button type="button" class="btn btn-warning button-custom button-back"
                                    data-to_step="4" data-step="5">Volver</button> 
                                    <button type="button" class="btn btn-warning button-custom button-next"
                                    data-to_step="6" data-step="5">Siguiente</button>
                                </div>
                            </div>

                            <!-- step 6 PROTESTA REGLAMENTO -->
                            <div class="row disabled" id="step-6">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5  style="text-align: center; margin-top:15vh">
                                                PROTESTO CUMPLIR CON LAS DISPOSICIONES ESTABLECIDAS EN EL MARCO NORMATIVO VIGENTE,<br>
                                                QUE RIGE LAS ACTIVIDADES DE LA UNIVERSIDAD DE LA SIERRA, ASÍ COMO CON LAS ACTIVIDADES <br>
                                                ACADÉMICAS QUE, CON MOTIVO DEL DESAROLLO DEL QUEHACER ACADÉMICO SE ME ASIGNEN.
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="offset-md-4"">
                                    {!! htmlFormSnippet() !!}
                                </div>
                                <div class="step_controls">
                                    <button type="button" class="btn btn-warning button-custom button-back"
                                    data-to_step="5" data-step="6">Volver</button> 
                                    <button type="button" id="btn-sumbit"  class="btn btn-warning button-custom button-sumbit"
                                    data-step="6">Enviar</button>
                                </div>
                            </div>
                                                            
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>

</div>

<script src="{{asset('js/alumn/form.js')}}"></script>

@stop
