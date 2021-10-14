@foreach ($carga as $item)
<tr>
    <td>
        {{ $loop->iteration }}
    </td>
    <td>
        {{ $item->Asignatura}}
    </td>
    <td style="padding-left: 3rem">
        {{ $item->Semestre}}
    </td>
    <td>
        {{ $item->profesor}}
    </td>
    <td>
        {{ $item->Calificacion}}
    </td>
  </tr>
    
@endforeach