    <table class="table table-striped table-bordered datatable-select-inputs w-100">
        <thead>
            <tr>
                <th>No.</th>
                <th>Vehicle Model</th>
                <th>Vehicle ID No.</th>
                <th>Lot Number.</th>
                <th>Engine No.</th>
                <th>Offline Date</th>
                <th>FCW Date</th>
            </tr>

        </thead>
        <tbody>
            @if (count($vehicles) > 0)
                @foreach ($vehicles as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->vehicle->model->model_name}}</td>
                    <td>{{$item->vehicle->vin_no}}</td>
                    <td>{{$item->vehicle->lot_no}}</td>
                    <td>{{$item->vehicle->engine_no}}</td>
                    <td>{{\Carbon\Carbon::createFromFormat('Y-m-d', $item->datetime_out)->format('d-M-Y')}}</td>
                    @if ($offdate[$item->vehicle_id] == "--")
                        <td>--</td>
                    @else
                        <td>{{\Carbon\Carbon::createFromFormat('Y-m-d', $offdate[$item->vehicle_id])->format('d-M-Y')}}</td>
                    @endif


                </tr>
                @endforeach
            @endif

        </tbody>

    </table>
