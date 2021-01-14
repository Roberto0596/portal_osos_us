@foreach ($data as $item)
<tr>
    <td>
        {{ $loop->iteration }}
    </td>
    <td>
        {{ $item["asignature"]}}
    </td>
    <td style="padding-left: 3rem">
        {{ $item["semester"]}}
    </td>
    <td>
        {{ $item["teacher"]}}
    </td>
    <td>
        {{ $item["score"]}}
    </td>
  </tr>
    
@endforeach