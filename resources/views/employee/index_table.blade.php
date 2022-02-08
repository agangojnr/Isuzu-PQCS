<table class="table table-striped table-bordered datatable-select-inputs">
    <thead>
        <tr>
            <th colspan="8">ISUZU EMPLOYEE LIST</th>

        </tr>
        <tr>
            <th>ITEM</th>
            <th>NAME</th>
            <th>STAFF NO</th>
            <th>DPARTMENT DESCRIPTION</th>
            <th>CATEGORY</th>
            <th>SHOP</th>
            <th>TEAMLEADER</th>
            <th>STATUS</th>
        </tr>
    </thead>
    <tbody>
        @if ($staffs != null)
        @foreach ($staffs as $item)
        <tr>
            <td>{{$item->unique_no}}</td>
            <td>{{$item->staff_name}}</td>
            <td>{{$item->staff_no}}</td>
            <td>{{$item->Department_Description}}</td>
            <td>{{$item->Category}}</td>
            <td>{{$item->shop->report_name}}</td>
            <td>
                @if ($item->team_leader == 'yes')
                    {{'Team Leader'}}
                @else
                    {{''}}
                @endif
                </td>

            <td>
                @if ($item->status == 'Active')
                    {{'Active'}}
                @else
                    {{'Inactive'}}
                @endif
            </td>


        </tr>
        @endforeach

        @endif

</table>
