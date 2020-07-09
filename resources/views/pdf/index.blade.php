@extends('layout')

@section('content')

<form  method="POST" action="{{ route('alumn.cedula',['digital','ver'])}}" style="width: 40%; margin: 5%;">
    @csrf
   
   
    <button type="submit" class="btn btn-primary" style="background-color: orange; border: none; float: left;margin: 10%">Cedula</button>
</form>
<form  method="POST" action="{{ route('alumn.constancia',['digital','ver'])}}" style="width: 40%; margin: 5%;">
    @csrf
    
    
    <button type="submit" class="btn btn-primary" style="background-color: orange; border: none; float: left;margin: 10%">Constancia</button>
</form>
<form  method="POST" action="{{ route('alumn.fichas',['digital','ver','transferencia'])}}" style="width: 40%; margin: 5%;">
    @csrf
    
    
    <button type="submit" class="btn btn-primary" style="background-color: orange; border: none; float: left; margin: 10%">Transferencia</button>
</form>
<form  method="POST" action="{{ route('alumn.fichas',['digital','ver','deposito'])}}" style="width: 40%; margin: 5%;">
    @csrf
    
    
    <button type="submit" class="btn btn-primary" style="background-color: orange; border: none; float: left; margin: 10%">Depósito</button>
</form>