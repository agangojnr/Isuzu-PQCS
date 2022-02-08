        <table class="tablesaw table-bordered table-hover"  id="pruebatabla">
            <thead>
                <th class="text-white">STAFF NO</th>
                <th class="text-white">EMPLOYEENAME</th>
                @for ($i = 0; $i < $count; $i++)
                    <th>{{$dates[$i]}}</th>
                @endfor
                <th>TOTAL</th>

            </thead>
            <tbody>
                @foreach ($employees as $emp)
                <tr>
                    <td class="text-white">{{$emp->staff_no}}</td>
                    <td class="text-white">{{$emp->staff_name}} <span class="text-danger">{{($emp->team_leader == "yes") ? "(T/L)" :"";}}</span></td>
                    @for ($i = 0; $i < $count; $i++)
                        <td>{{round(($emphrs[$emp->id][$i]/8),1)}}</td>
                    @endfor
                    <th>{{round($ttemphrs[$emp->id],2)}}</th>

                </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td class="text-white">TOTAL PRESENT</td>
                    @for ($i = 0; $i < $count; $i++)
                        <td>{{$ttpresent[$i]}}</td>
                    @endfor
                    <td>{{round($ttsum,2)}}</td>

                </tr>
                <tr>
                    <td></td>
                    <td class="text-white">TOTAL ABSENT</td>
                    @for ($i = 0; $i < $count; $i++)
                        <td>{{$ttemp[$i] - $ttpresent[$i]}}</td>
                    @endfor
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-white">T.L AVAILABILITY %</td>
                    @for ($i = 0; $i < $count; $i++)
                        <td>{{$tlavail[$i]}}%</td>
                    @endfor
                    <td></td>
                </tr>
            </tbody>

        </table>