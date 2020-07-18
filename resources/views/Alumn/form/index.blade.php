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
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('alumn.home')}}">Home</a></li>
                <li class="breadcrumb-item active">Re-Inscripción</li>
              </ol>
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

                        <form class="form-inscription">

                            <input  id="token"  type="hidden" value="{{csrf_token()}}"/> 

                            {{ csrf_field() }}                                            
                            <!-- step 1  DATOS PERSONALES I -->

                            <div class="row active" id="step-1">

                                <div class="col-md-12">

                                    <div class="row ">

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label class="control-label">Primer Apellido</label>

                                                <input name="ApellidoPrimero"  id="ApellidoPrimero" class="form-control capitalize" placeholder="Ingrese su primer apellido" isnullable="no" value="{{ $data['ApellidoPrimero'] == null ? '' : $data['ApellidoPrimero'] }}" required>

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label class="control-label">Segundo Apellido</label>

                                                <input name="ApellidoSegundo" id="ApellidoSegundo" class="form-control capitalize" placeholder="Ingrese su segundo apellido" isnullable="no" value="{{ $data['ApellidoSegundo'] == null ? '' : $data['ApellidoSegundo'] }}" required>

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label class="control-label">Nombre</label>

                                                <input id="Nombre" name="Nombre" class="form-control capitalize" isnullable="no" placeholder="Ingrese su nombre" value="{{ $data['Nombre'] == null ? '' : $data['Nombre'] }}" required>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-2">

                                            <div class="form-group">

                                                <label class="control-label">Domicilio</label>

                                                <input id="Domicilio" name="Domicilio" isnullable="si" class="form-control capitalize" placeholder="Ingrese su domicilio" value="{{ $data['Domicilio'] == null ? '' : $data['Domicilio'] }}">

                                            </div>

                                        </div>

                                        <div class="col-md-2">

                                            <div class="form-group">

                                                <label class="control-label">Colonia</label>

                                                <input id="Colonia" name="Colonia" class="form-control capitalize" 
                                                isnullable="no" placeholder="Ingrese su colonia" value="{{ $data['Colonia'] == null ? '' : $data['Colonia'] }}">

                                            </div>

                                        </div>

                                        <div class="col-md-2">

                                            <div class="form-group">

                                                <label class="control-label">Localidad</label>

                                                <input id="Localidad" name="Localidad" class="form-control capitalize"
                                                isnullable="si" placeholder="Ingrese su localidad" value="{{ $data['Localidad'] == null ? '' : $data['Localidad'] }}">

                                            </div>

                                        </div>

                                        <div class="col-md-3">

                                            <div class="form-group">

                                                <label for="EstadoDom" data-alias="estado" class="control-label">Estado</label>

                                                <select id="EstadoDom" name="EstadoDom"  isnullable="no" class="form-control select2">
                                                    <option  disabled="" selected="">Seleccionar</option>
                                                    @if($data["EstadoDom"] != null)
                                                        @php
                                                            $edoSelected = selectSicoes("Estado","EstadoId",$data["EstadoDom"])[0]; 
                                                            var_dump($edoSelected);
                                                        @endphp                                                    
                                                    <option value="{{$edoSelected['EstadoId']}}" selected> {{$edoSelected['Nombre']}} </option>
                                                    @else
                                                    <option  disabled="" selected="">Seleccionar</option>
                                                    @endif

                                                    @foreach ($estados as $edo)
                                                    <option value="{{$edo['Clave']}}"> {{$edo['Nombre']}} </option>
                                                    @endforeach
                                                </select>

                                            </div>

                                        </div>

                                        <div class="col-md-3">

                                            <div class="form-group">

                                                <label for="MunicipioDom" data-alias="municipio" class="control-label">Municipio</label>

                                                <select id="MunicipioDom" name="MunicipioDom" isnullable="no" class="form-control select2">
                                                    @if($data["MunicipioDom"] != null)
                                                    @php
                                                        $mpioSelected = selectSicoes("Municipio","MunicipioId",$data["MunicipioDom"])[0]; 
                                                    @endphp                                                    
                                                    <option value="{{$mpioSelected['MunicipioId']}}"> {{$mpioSelected['Nombre']}} </option>
                                                    @else
                                                    <option  disabled="" selected="">Seleccionar</option>
                                                    @endif
                                                </select>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-2">

                                            <div class="form-group">

                                                <label class="control-label">Código Postal</label>

                                                <input type="text" class="form-control codigo" id="CodigoPostal" name="CodigoPostal" isnullable="no" placeholder="Ej. 84330" value="{{ $data['CodigoPostal'] == null ? '' : $data['CodigoPostal'] }}" required>

                                            </div>

                                        </div>

                                        <div class="col-md-2">

                                            <div class="form-group">

                                                <label class="control-label">Teléfono</label>

                                                <input id="Telefono" type="text" name="Telefono" class="form-control phone" placeholder="Ingrese su numero" isnullable="si" value="{{ $data['Telefono'] == null ? '' : $data['Telefono'] }}">

                                            </div>

                                        </div>

                                        <div class="col-md-3">

                                            <div class="form-group">

                                                <label for="estadonac" data-alias="estadonac" class="control-label">Estado de Nacimiento</label>

                                                <select id="EstadoNac" isnullable="si" name="EstadoNac" class="form-control select2" >
                                                    <option  disabled="" selected="">Seleccionar</option>
                                                    @if($data["EstadoNac"] != null)
                                                    @php
                                                        $edoSelected = selectSicoes("Estado","EstadoId",$data["EstadoNac"])[0]; 
                                                    @endphp                                                    
                                                    <option value="{{$edoSelected['EstadoId']}}" selected> {{$edoSelected['Nombre']}} </option>
                                                    @else
                                                    <option  disabled="" selected="">Seleccionar</option>
                                                    @endif

                                                    @foreach ($estados as $edo)
                                                    <option value="{{$edo['Clave']}}"> {{$edo['Nombre']}} </option>
                                                    @endforeach
                                                </select>

                                            </div>

                                        </div> 
                                            
                                        <div class="col-md-3">

                                            <div class="form-group">

                                                <label for="municipionac" data-alias="municipionac" class="control-label">Municipio de Nacimiento</label>
                                                
                                                <select id="MunicipioNac" name="MunicipioNac" isnullable="si" class="form-control select2">
                                                    @if($data["MunicipioNac"] != null)
                                                    @php
                                                        $mpioSelected = selectSicoes("Municipio","MunicipioId",$data["MunicipioNac"])[0]; 
                                                    @endphp                                                    
                                                    <option value="{{$mpioSelected['MunicipioId']}}"> {{$mpioSelected['Nombre']}} </option>
                                                    @else
                                                    <option  disabled="" selected="">Seleccionar</option>
                                                    @endif
                                                </select>

                                            </div>

                                        </div>                                                               

                                        <div class="col-md-2">

                                            <div class="form-group">

                                                <label for="edocivil" data-alias="Edo.Civil" class="control-label">Edo.Civil</label>

                                                <select id="EdoCivil" isnullable="no" name="EdoCivil" class="form-control" required>
                                                    @if($data["EdoCivil"] != null)
                                                    @php
                                                        $value = $data["EdoCivil"] == '0' ? 'SOLTERO' : 'CASADO';
                                                    @endphp                                                    
                                                    <option disabled value="{{$data['EdoCivil']}}"> {{$value}} </option>
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
                                                <input id="Curp" name="Curp" isnullable="si" maxlength="18" min="18" type="text" class="form-control capitalize" placeholder="Ingrese su curp" value="{{ $data['Curp'] == null ? '' : $data['Curp'] }}">
                                            </div>

                                        </div>

                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="control-label">Fecha de nacimiento</label>
                                                <input type="text"  isnullable="no" class="form-control date" 
                                                id="FechaNacimiento" name="FechaNacimiento" required value="{{ $data['FechaNacimiento'] == null ? '' : $data['FechaNacimiento'] }}">
                                            </div>

                                        </div>

                                        <div class="col-md-3">

                                            <div class="form-group">

                                                <label for="genero" data-alias="genero" class="control-label">Género</label>

                                                <select id="Genero" isnullable="no" name="Genero" class="form-control" required>
                                                    @if($data["Genero"] != null)
                                                    @php
                                                        $value = $data["Genero"] == '0' ? 'HOMBRE' : 'MUJER';
                                                    @endphp                                                    
                                                    <option value="{{$data['Genero']}}"> {{$value}} </option>
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
                                                <input id="Email" isnullable="si" type="email" name="Email" class="form-control" placeholder="Ingrese su correo" value="{{ $data == null ? '' : $data['Email'] }}">

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label for="tiposangre" data-alias="tiposangre" class="control-label">Tipo de sangre</label>

                                                <select id="TipoSangre" isnullable="no" name="TipoSangre" class="form-control" >
                                                    <option value="" disabled="" selected="">Seleccionar</option>
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
                                                    <option value="{{$data['TipoSangre']}}" selected> {{$value}} </option>
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
                                                <input id="Alergias" name="Alergias"  isnullable="si" id="descAlergia" maxlength="100" type="text" class="form-control capitalize" placeholder="Especifique su alergia" value="{{ $data == null ? '' : $data['Alergias'] }}">
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label class="control-label"><output></output>Padecimiento</label>
                                                <input name="Padecimiento" isnullable="si" id="Padecimiento" type="text" class="form-control capitalize" placeholder="Especifique su padecimiento" value="{{ $data == null ? '' : $data['Padecimiento'] }}">

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

                                                <input id="NumAfiliacion" name="NumAfiliacion" isnullable="si" type="text"  class="form-control eleven" placeholder="Ingrese su No. Afiliación" value="{{ $data['NumAfiliacion'] == null ? '' : $data['NumAfiliacion'] }}">

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label class="control-label">Contacto en caso de Emergencia</label>
                                                <input id="ContactoEmergencia" name="ContactoEmergencia" isnullable="si" type="text" class="form-control capitalize" placeholder="Ingrese su contacto" value="{{ $data['ContactoEmergencia'] == null ? '' : $data['ContactoEmergencia'] }}">

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label class="control-label">Domicilio</label>
                                                <input id="ContactoDomicilio" name="ContactoDomicilio" isnullable="si"  type="text" class="form-control capitalize" placeholder="Ingrese su domicilio" value="{{ $data['ContactoDomicilio'] == null ? '' : $data['ContactoDomicilio'] }}">
                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label class="control-label">Teléfono</label>

                                                <input id="ContactoTelefono" name="ContactoTelefono" isnullable="si"  
                                                type="text" class="form-control phone" placeholder="Ej. 6558036422" value="{{ $data['ContactoTelefono'] == null ? '' : $data['ContactoTelefono'] }}" >

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

                                                <input type="text" disabled value="{{ $data['Matricula'] == null ? '' : $data['Matricula'] }}" class="form-control" placeholder="Ingrese su matricula">

                                            </div>

                                        </div>

                                        <div class="col-md-6">

                                            <div class="form-group">

                                                <label for="Carrera" class="control-label">Carrera</label>

                                                <select class="form-control " disabled>
                                                    @if ($data != null)

                                                    @php
                                                        $data_studio = selectSicoes("PlanEstudio","PlanEstudioId",$data["PlanEstudioId"]);
                                                        $data_carrer = selectSicoes("Carrera","CarreraId",$data_studio[0]["CarreraId"]);
                                                    @endphp

                                                    <option value="{{ $data_studio[0]['CarreraId']}}" disabled="" selected="">{{$data_carrer[0]['Nombre']}}</option>

                                                    @endif                            
                                                    
                                                </select>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-3">

                                            <div class="form-group">

                                                <label for="plan" data-alias="plan" class="control-label">Plan De Estudios</label>

                                                <select class="form-control " disabled>
                                                    @if ($data != null)
                                                    @php
                                                        $data_studio = selectSicoes("PlanEstudio","PlanEstudioId",$data["PlanEstudioId"]);
                                                    @endphp
                                                    <option value="{{ $data['PlanEstudioId']}}" disabled="" selected="">{{$data_studio[0]['Nombre']}}</option>
                                                    @endif
                                                    
                                                </select>

                                            </div>

                                        </div>

                                        <div class="col-md-3">

                                            <div class="form-group">

                                                <label for="Periodo" data-alias="Periodo" class="control-label">Periodo</label>

                                                <select class="form-control " disabled>
                                                    @if ($data != null)
                                                    @php
                                                        $period = selectCurrentPeriod();
                                                    @endphp
                                                    <option value="{{ $period['PeriodoId']}}" disabled="" selected="">{{$period['Clave']}}</option>
                                                    @endif
                                                </select>

                                            </div>

                                        </div>

                                        <div class="col-md-3">

                                            <div class="form-group">

                                                <label for="Semestre" data-alias="Semestre" class="control-label">Semestre</label>

                                                <select class="form-control" disabled>
                                                    @if ($data != null)
                                                    @php
                                                        $lastSemester =getLastSemester($currentId) + 1;
                                                    @endphp
                                                    <option disabled="" selected="">{{$lastSemester}}</option>
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

                                                <select id="EscuelaProcedenciaId" name="EscuelaProcedenciaId" class="form-control " disabled>

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

                                                <input maxlength="4" minlength="4" type="text"  class="form-control" disabled
                                                value="{{ $data == null ? '' : $data['AnioEgreso'] }}"
                                                placeholder="Ej. 1999"  />

                                            </div>

                                        </div>

                                        <div class="col-md-3">

                                            <div class="form-group">

                                                <label class="control-label"><output></output>Promedio</label>

                                                <input maxlength="4" minlength="4" type="text" class="form-control" disabled
                                                value="{{ $data == null ? '' : $data['PromedioBachiller'] }}"
                                                placeholder="Ej. 1999">

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

                                                <input type="text" name="TutorNombre" id="TutorNombre" class="form-control capitalize" placeholder="Ingrese el nombre del tutor" value="{{ $data == null ? '' : $data['TutorNombre']}}" >

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label class="control-label">Domicilio del tutor</label>

                                                <input type="text" name="TutorDomicilio" id="TutorDomicilio" class="form-control capitalize" placeholder="Ingrese el domicilio del tutor" value="{{ $data == null ? '' : $data['TutorDomicilio']}}">

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label class="control-label">Teléfono del tutor</label>

                                                <input type="text" name="TutorTelefono" id="TutorTelefono" class="form-control phone" placeholder="Ingrese el teléfono del tutor" value="{{ $data == null ? '' : $data['TutorTelefono']}}" >

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-6">

                                            <div class="form-group">

                                                <label class="control-label">Ocupación del tutor</label>

                                                <input type="text" name="TutorOcupacion" id="TutorOcupacion"  class="form-control capitalize" placeholder="Ingrese la ocupación del tutor" value="{{ $data == null ? '' : $data['TutorOcupacion']}}" >

                                            </div>

                                        </div>

                                        <div class="col-md-6">

                                            <div class="form-group">

                                                <label class="control-label">Sueldo mensual del tutor</label>

                                                <input step="any" type="number" name="TutorSueldoMensual" id="TutorSueldoMensual" class="form-control"
                                                 placeholder="Ingrese el sueldo mensual del tutor" value="{{ $data == null ? '' : $data['TutorSueldoMensual']}}" >

                                            </div>

                                        </div>
                                        
                                    </div>

                                    <div class="row">

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label class="control-label">Nombre completo de la madre</label>
                                                <input type="text" id="MadreNombre" name="MadreNombre" class="form-control capitalize" placeholder="Ingrese el nombre de la madre" value="{{ $data == null ? '' : $data['MadreNombre']}}">

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label class="control-label">Domicilio de la madre</label>

                                                <input type="text" id="MadreDomicilio" name="MadreDomicilio" class="form-control capitalize" placeholder="Ingrese el domicilio de la madre" value="{{ $data == null ? '' : $data['MadreDomicilio']}}" >

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label class="control-label">Teléfono de la madre</label>

                                                <input type="text" id="MadreTelefono" name="MadreTelefono" class="form-control phone" placeholder="Ingrese el teléfono de la madre" value="{{ $data == null ? '' : $data['MadreTelefono']}}" >

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

                                                <select  name="TrabajaActualmente" class="form-control" id="TrabajaActualmente" isnullable="no" required>
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

                                                <input id="Puesto" name="Puesto" maxlength="100" type="text" class="form-control"
                                                isnullable="si" placeholder="Ingrese el puesto" readonly value="{{ $data['Puesto'] == null ? '' : $data['Puesto']}}">

                                            </div>

                                        </div>

                                        <div class="col-md-4">
                                            
                                            <div class="form-group">

                                                <label class="control-label">Sueldo mensual del alumno</label>

                                                <input type="text" id="SueldoMensualAlumno" class="form-control" name="SueldoMensualAlumno"
                                                isnullable="si" placeholder="Ingrese el sueldo mensual del alumno" readonly value="{{ $data['SueldoMensualAlumno'] == null ? '' : $data['SueldoMensualAlumno']}}">

                                            </div>
                                            
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-6">

                                            <div class="form-group">

                                                <label for="transporteuniversidad" data-alias="transporteuniversidad" class="control-label">¿utiliza el transporte unisierra?</label>

                                                <select  name="TransporteUniversidad" class="form-control" id="TransporteUniversidad" required>

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
                                                
                                                <select name="Transporte" class="form-control" id="Transporte" disabled>
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

                                                <input type="text"  isnullable="si" class="form-control capitalize" name="DeportePractica" id="DeportePractica" placeholder="ej.Danza" value="{{ $data['DeportePractica'] == null ? '' : $data['DeportePractica']}}">

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
                                <div class="offset-md-4">
                                    {!! htmlFormSnippet() !!}
                                </div>
                                <div class="step_controls">
                                    <button type="button" class="btn btn-warning button-custom button-back"
                                    data-to_step="5" data-step="6">Volver</button> 
                                    <button type="button"  class="btn btn-warning button-custom">Enviar</button>
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
