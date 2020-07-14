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

                        <form class="form-inscription" method="POST" action="{{route('alumn.save.inscription')}}">

                            {{ csrf_field() }}                                            
                            <!-- step 1  DATOS PERSONALES I -->
                            @php
                                $lastnames = explode(" ", $user->lastname);
                            @endphp
                            <div class="row active" id="step-1">

                                <div class="col-md-12">

                                    <div class="row ">

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label class="control-label">Primer Apellido</label>

                                                <input name="ApellidoPrimero"  id="ApellidoPrimero" class="form-control capitalize" placeholder="Ingrese su primer apellido" value="{{$lastnames[0]}}" isnullable="no" required>

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label class="control-label">Segundo Apellido</label>

                                                <input name="ApellidoSegundo" id="ApellidoSegundo" class="form-control capitalize" placeholder="Ingrese su segundo apellido" value="{{$lastnames[1]}}" isnullable="no" required>

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label class="control-label">Nombre</label>

                                                <input id="Nombre" name="Nombre" class="form-control capitalize" isnullable="no" placeholder="Ingrese su nombre" value="{{$user->name}}" required>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-2">

                                            <div class="form-group">

                                                <label class="control-label">Domicilio</label>

                                                <input id="Domicilio" name="Domicilio" isnullable="si" class="form-control capitalize" placeholder="Ingrese su domicilio">

                                            </div>

                                        </div>

                                        <div class="col-md-2">

                                            <div class="form-group">

                                                <label class="control-label">Colonia</label>

                                                <input id="Colonia" name="Colonia" class="form-control capitalize" 
                                                isnullable="no" placeholder="Ingrese su colonia">

                                            </div>

                                        </div>

                                        <div class="col-md-2">

                                            <div class="form-group">

                                                <label class="control-label">Localidad</label>

                                                <input id="Localidad" name="Localidad" class="form-control capitalize"
                                                isnullable="si" placeholder="Ingrese su localidad" required>

                                            </div>

                                        </div>

                                        <div class="col-md-3">

                                            <div class="form-group">

                                                <label for="MunicipioDom" data-alias="municipio" class="control-label">Municipio</label>

                                                <select id="MunicipioDom" name="MunicipioDom" isnullable="no" class="form-control select2">

                                                    <option  disabled="" selected="">Seleccionar</option>
                                                    @foreach ($municipios as $mpio)
                                                    <option value="{{$mpio['Clave']}}"> {{$mpio['Nombre']}} </option>
                                                    @endforeach

                                                </select>

                                            </div>

                                        </div>

                                        <div class="col-md-3">

                                            <div class="form-group">

                                                <label for="EstadoDom" data-alias="estado" class="control-label">Estado</label>
                                                <select id="EstadoDom" name="EstadoDom"  isnullable="no" class="form-control select2">
                                                    <option  disabled="" selected="">Seleccionar</option>
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

                                                <input maxlength="5" min="5" type="number" class="form-control" id="CodigoPostal" name="CodigoPostal" isnullable="no" placeholder="Ej. 84330">

                                            </div>

                                        </div>

                                        <div class="col-md-2">

                                            <div class="form-group">

                                                <label class="control-label">Teléfono</label>

                                                <input id="Telefono" type="text" name="Telefono" class="form-control phone" placeholder="Ingrese su numero" isnullable="si">

                                            </div>

                                        </div>
                                            
                                        <div class="col-md-3">

                                            <div class="form-group">

                                                <label for="municipionac" data-alias="municipionac" class="control-label">Municipio de Nacimiento</label>
                                                
                                                <select id="MunicipioNac" name="MunicipioNac" isnullable="si" class="form-control select2" >
                                                    <option  disabled="" selected="">Seleccionar</option>
                                                    @foreach ($municipios as $mpio)
                                                    <option value="{{$mpio['Clave']}}"> {{$mpio['Nombre']}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                            
                                        
                                        <div class="col-md-3">

                                            <div class="form-group">

                                                <label for="estadonac" data-alias="estadonac" class="control-label">Estado de Nacimiento</label>

                                                <select id="EstadoNac" isnullable="si" name="EstadoNac" class="form-control select2" >
                                                    <option  disabled="" selected="">Seleccionar</option>
                                                    @foreach ($estados as $edo)
                                                    <option value="{{$edo['Clave']}}"> {{$edo['Nombre']}} </option>
                                                    @endforeach
                                                </select>

                                            </div>

                                        </div>                                            

                                        <div class="col-md-2">

                                            <div class="form-group">

                                                <label for="edocivil" data-alias="Edo.Civil" class="control-label">Edo.Civil</label>

                                                <select id="EdoCivil" isnullable="no" name="EdoCivil" class="form-control" required>
                                                    <option  disabled="" selected="">Seleccionar</option>                    
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
                                                <input id="Curp" name="Curp" isnullable="si" maxlength="18" min="18" type="text" class="form-control capitalize" placeholder="Ingrese su curp">
                                            </div>

                                        </div>

                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="control-label">Fecha de nacimiento</label>
                                                <input type="date"  isnullable="no" class="form-control" 
                                                id="AñoNacimiento" name="AñoNacimiento" required>
                                            </div>

                                        </div>

                                        <div class="col-md-3">

                                            <div class="form-group">

                                                <label for="genero" data-alias="genero" class="control-label">Género</label>
                                                <select id="genero" isnullable="no" name="Genero" class="form-control" required>
                                                    <option  disabled="" selected="">Seleccionar</option>                             
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
                                                <input id="Email" isnullable="si" type="email" name="Email" class="form-control" placeholder="Ingrese su correo" value="{{$user->email}}">

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label for="tiposangre" data-alias="tiposangre" class="control-label">Tipo de sangre</label>

                                                <select id="TipoSangre" isnullable="no" name="TipoSangre" class="form-control" >
                                                    <option value="" disabled="" selected="">Seleccionar</option>
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
                                                <input id="Alergias" name="Alergias"  isnullable="si" id="descAlergia" maxlength="100" type="text" class="form-control capitalize"          placeholder="Especifique su alergia">
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label class="control-label"><output></output>Padecimiento</label>
                                                <input name="Padecimiento" isnullable="si" id="descPadecimiento" type="text" class="form-control capitalize" placeholder="Especifique su padecimiento">

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label for="servicioMedico" data-alias="servicioMedico" class="control-label">Servicio Médico</label>

                                                <select id="ServicioMedico" name="ServicioMedico" class="form-control" id="ServicioMedico" isnullable="si">
                                                    <option value="" disabled="" selected="">Seleccione un item</option>
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

                                                <input id="NumAfiliacion" name="NumAfiliacion" isnullable="si" type="text"  class="form-control eleven" placeholder="Ingrese su No. Afiliación">

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label class="control-label">Contacto en caso de Emergencia</label>
                                                <input id="ContactoEmergencia" name="ContactoEmergencia" isnullable="si" type="text" class="form-control capitalize" placeholder="Ingrese su contacto">

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label class="control-label">Domicilio</label>
                                                <input id="ContactoDomicilio" name="ContactoDomicilio" isnullable="si"  type="text" class="form-control capitalize" placeholder="Ingrese su domicilio">
                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label class="control-label">Teléfono</label>

                                                <input id="ContactoTelefono" name="ContactoTelefono" isnullable="si"  
                                                type="text" class="form-control phone" placeholder="Ej. 6558036422">

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

                                                <label for="Carrera" class="control-label">Carrera</label>

                                                <select id="Carrera" name="Carrera" isnullable="no" class="form-control" required>

                                                    @php                                                        
                                                        $school = selectSicoes("Carrera");
                                                    @endphp

                                                    <option value="" disabled="" selected="">Seleccionar</option>

                                                    @foreach($school as $key => $value)
                                                    <option value="{{$value['CarreraId']}}">{{$value["Nombre"]}}</option>
                                                    @endforeach                             
                                                    
                                                </select>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-5">

                                            <div class="form-group">

                                                <label for="escuelaprocedencia" data-alias="escuelaprocedencia" class="control-label">Escuela de Procedencia</label>

                                                <select id="EscuelaProcedenciaId" name="EscuelaProcedenciaId" class="form-control" isnullable="si">

                                                    @php                                                        
                                                        $school = selectSicoes("Escuela");
                                                    @endphp

                                                    <option value="" disabled="" selected="">Seleccionar</option>

                                                    @foreach($school as $key => $value)
                                                    <option value="{{$value['EscuelaId']}}">{{$value["Nombre"]}}</option>
                                                    @endforeach

                                                </select>
                                                    
                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label class="control-label">Año de Egreso</label>
                                                <input  maxlength="4" minlength="4" type="text" class="form-control"
                                                placeholder="Ej. 1999" id="AnioEgreso" name="AnioEgreso">

                                            </div>

                                        </div>

                                        <div class="col-md-3">

                                            <div class="form-group">

                                                <label class="control-label">Promedio</label>
                                                <input type="number" step="any" class="form-control" placeholder="¿Cual fue su promedio?" id="Promedio" name="Promedio">

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

                                                <input type="text" name="TutorNombre" id="TutorNombre" class="form-control capitalize" placeholder="Ingrese el nombre del tutor">

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label class="control-label">Domicilio del tutor</label>

                                                <input type="text" name="TutorDomicilio" id="TutorDomicilio" class="form-control capitalize" placeholder="Ingrese el domicilio del tutor">

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label class="control-label">Teléfono del tutor</label>

                                                <input type="text" name="TutorTelefono" id="TutorTelefono" class="form-control phone" placeholder="Ingrese el teléfono del tutor">

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-6">

                                            <div class="form-group">

                                                <label class="control-label">Ocupación del tutor</label>

                                                <input type="text" name="TutorOcupacion" id="TutorOcupacion"  class="form-control capitalize" placeholder="Ingrese la ocupación del tutor">

                                            </div>

                                        </div>

                                        <div class="col-md-6">

                                            <div class="form-group">

                                                <label class="control-label">Sueldo mensual del tutor</label>

                                                <input step="any" type="number" name="TutorSueldoMensual" id="TutorSueldoMensual" class="form-control"
                                                 placeholder="Ingrese el sueldo mensual del tutor">

                                            </div>

                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Nombre completo de la madre</label>
                                                <input fieldname="Nombre completo de la Madre" maxlength="100" type="text"
                                                    name="MadreNombre"   class="form-control"
                                                 placeholder="Ingrese el nombre de la madre"  />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Domicilio de la madre</label>
                                                <input fieldname="Domicilio de la madre" maxlength="100" type="text"
                                                name="MadreDomicilio" class="form-control"
                                                placeholder="Ingrese el domicilio de la madre"  />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Teléfono de la madre</label>
                                                <input fieldname="Teléfono de la Madre" maxlength="10" type="text"   name="MadreTelefono"  class="form-control phone"
                                                 placeholder="Ingrese el teléfono de la madre"  />
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
                                                    <option  disabled="" selected="">Seleccionar</option>
                                                    <option value="0">NO</option>
                                                    <option value="1">SI</option>
                                                </select>
                                                </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="control-label">Puesto</label>
                                                <input id="Puesto" name="Puesto" fieldname="Puesto" maxlength="100" type="text" disabled  class="form-control"
                                                isnullable="si" placeholder="Ingrese el puesto" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            
                                            <div class="form-group">
                                                <label class="control-label">Sueldo mensual del alumno</label>
                                                <input fieldname="Sueldo mensual del alumno" maxlength="100" type="text" disabled id="SueldoMensualAlumno"  
                                                class="form-control" name="SueldoMensualAlumno"
                                                isnullable="si" placeholder="Ingrese el sueldo mensual del alumno"  />
                                            </div>
                                            
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-6">

                                            <div class="form-group">

                                                <label for="transporteuniversidad" data-alias="transporteuniversidad" class="control-label">¿utiliza el transporte unisierra?</label>

                                                <select  name="TransporteUniversidad" class="form-control" id="TransporteUniversidad" required>

                                                    <option  disabled="" selected="">Seleccionar</option>
                                                    <option value="0">No</option>
                                                    <option value="1">Si</option>

                                                </select>

                                            </div>

                                        </div>

                                        <div class="col-md-6">

                                            <div class="form-group">

                                                <label for="transporte" data-alias="transporte"  class="control-label">¿Cual?</label>
                                                
                                                <select  name="Transporte" class="form-control"   id="Transporte" >
                                                    <option  disabled="" selected="">SELECCIONAR</option>
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

                                                <input fieldname="Actividad cultural" maxlength="100" type="text"  isnullable="si" class="form-control" name="DeportePractica" id="DeportePractica"
                                                placeholder="ej.Danza"/>

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
                                    <button type="submit"  class="btn btn-warning button-custom">Enviar</button>
                                </div>
                            </div>
                                                            
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>

</div>

<script src="{{asset('js/alumn/inscription.js')}}"></script>

@stop
