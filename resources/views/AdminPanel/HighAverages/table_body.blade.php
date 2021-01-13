@foreach ($alumns as $alumn)


@php
    $fullname = $alumn["Nombre"]." ".$alumn["ApellidoPrimero"]." ".$alumn["ApellidoSegundo"];
@endphp


<tr>
    <td class="sorting_1">{{ $loop->iteration }}</td>
    <td>{{ $alumn["Matricula"] }}</td>
    <td >{{ $fullname }}</td>
    <td>
        <div class="btn-group">
            <button class="btn btn-success btnAdd" alumn_enrollment="{{$alumn["Matricula"]}}"
             title="agregar"><i class="fa fa-plus"></i></button>
        </div>
    </td>
</tr>
    
@endforeach