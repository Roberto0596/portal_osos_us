@extends('layout')

@section('content')

<form  method="POST" action="{{ route('alumn.generar',['digital','ver'])}}" style="width: 40%; margin: 5%;">
    @csrf
    <div class="form-group">
        <label for="matricula">Matrícula del alumno</label>
        <input type="text" class="form-control" name="matriculaAlumno" id="matricula" aria-describedby="matriculaHelp" placeholder="Ingrese matrícula">
    </div>
    <button type="submit" class="btn btn-primary" style="background-color: orange; border: none; float: left;">Cedula</button>
    <button type="submit" class="btn btn-primary" style="background-color: orange; border: none; float: right;">Cedula</button>
</form>