@forelse ($units as $unit)
    <tr>
        <td>
            {{$unit->lot_no}}

            <input type="hidden" name="units[{{$loop->index + $index}}][unit_id]" value="{{$unit->id}}">   
        </td>
        <td>
            {{$unit->job_no}}

             
        </td>
          <td>
            {{$unit->vin_no}}

             
        </td>
        <td>
            <input type="number" class="form-control" min=1
            name="units[{{$loop->index + $index}}][quantity]" value="2">
        </td>
    </tr>
@empty

@endforelse