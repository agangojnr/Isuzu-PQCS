<table class="tablesaw table-bordered table-hover"  id="pruebatabla">

    <thead style="color: black;">
        @if (!empty($emps))
        <tr>
            <th  class="hdgeneral"></th>
            <th class="hdgeneral"></th>
            <th class="hdgeneral"></th>
            <th class="hdgeneral" colspan="{{count($dates)}}">
            <h4 class="text-white">DEPT: MANUFACTURING AND SUPPLY CHAIN</h4>
            </th>
            <th class="hdgeneral"></th>
            <th class="hdgeneral"></th>
            <th class="hdgeneral"></th>
            <th class="hdgeneral"></th>
        </tr>
        <tr>
            <th class="hdgeneral"></th>
            <th class="hdgeneral"></th>
            <th class="hdgeneral"></th>
            <th class="hdgeneral" colspan="{{count($dates)}}">
                <h5 class="text-white">SECTION: {{$shopname}} {{$range}}</h5>
            </th>
            <th class="hdgeneral"></th>
            <th class="hdgeneral"></th>
            <th class="hdgeneral"></th>
            <th class="hdgeneral"></th>
        </tr>
        <tr>
            <th class="hdgeneral">No.</th>
            <th class="hdgeneral">Staff No</th>
            <th class="hdgeneral">EmployeeName</th>
            @for ($i = 0; $i < count($dates); $i++)
            <th class="hdgeneral">{{$wkdys[$i]}}<br>{{$dates[$i]}}</th>
            @endfor
            <th class="hdgeneral"><b>@1.5</b></th>
            <th class="hdgeneral"><b>@2</b></th>
            <th class="hdgeneral">Total</th>
            <th class="hdgeneral">SIGN</th>

        </tr>
    <thead>
        <tbody style="color: black;">
            @foreach ($emps as $emp)
            <tr>
                <td class="normal text-white">{{$loop->iteration}}.</td>
                <td class="normal text-white">{{$emp->staff_no}}</td>
                <td class="normal text-white">{{$emp->staff_name}}</td>
                @for ($i = 0; $i < count($dates); $i++)

                <td class="normal"
                    @if($holi[$i] == "SU_H")
                        style="background: rgb(126, 128, 128)"
                    @elseif($holi[$i] == "SAT")
                        style="background: rgb(223, 224, 224)"
                    @elseif($holi[$i] == "-")
                        style="background: rgb(255, 255, 255)"
                    @endif >
                        @if ($authshow[$emp->id][$i])
                        <span class="text-danger">{{($othrs[$emp->id][$i] > 0) ? $othrs[$emp->id][$i] : 'x'}}</span>
                        @else
                        {{($othrs[$emp->id][$i] > 0) ? $othrs[$emp->id][$i] : 'x'}}
                        @endif
                    </td>

                @endfor
                <td class="normal"><b>{{$saturday[$emp->id]}}</b></td>
                <td class="normal"><b>{{$sunday[$emp->id]}}</b></td>
                <td class="normal">{{$emptthrs[$emp->id]}}</td>
                <td class="normal"></td>

            </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td><td></td>
                @for ($i = 0; $i < count($dates); $i++)
                <td></td>
                @endfor
                <td></td><td></td><td></td><td></td>
            </tr>
            <tr>
                <td></td>
                <td></td><td></td>
                @for ($i = 0; $i < count($dates); $i++)
                <td></td>
                @endfor
                <td></td><td></td><td></td><td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="text-white">Hours Authorised</td>
                @for ($i = 0; $i < count($dates); $i++)
                <td>{{round($cumauthhrs[$i],2)}}</td>
                @endfor
                <td></td><td></td><td></td><td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="text-white">Hours Worked</td>
                @for ($i = 0; $i < count($dates); $i++)
                <th>{{round($ttothrs[$i],2)}}</th>
                @endfor
                <td></td><td></td><td></td><td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="text-white">Variance</td>
                @for ($i = 0; $i < count($dates); $i++)
                <td>{{round(($cumauthhrs[$i]) - $ttothrs[$i],2)}}</td>
                @endfor
                <td></td><td></td><td></td><td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="text-white">Cum Hrs Worked</td>
                @for ($i = 0; $i < count($dates); $i++)
                <td>{{$cumttothrs[$i]}}</td>
                @endfor
                <td></td><td></td><td></td><td></td>
            </tr>
        @endif

        <tr>
            <td></td>
            <td></td><td></td>
            @for ($i = 0; $i < count($dates); $i++)
            <td></td>
            @endfor
            <td></td>
            <td></td><td></td><td></td>
        </tr>
        <!--FOOTER SECTION-->

        <tr>
            <td></td>
            <td></td><td></td>
            <td colspan="{{count($dates)}}"><b>Notes:</b></td>
            <td></td>
            <td></td><td></td><td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($dates)}}">
                <b>1.Within any 2 weeks window period shown above,overtime shall not exceed 36 hours.</b>
            </td>
            <td></td>
            <td></td><td></td><td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="{{count($dates)}}">
                <b>2.For a month with more than 4 weeks,cummulative overtime hours may exceed 72 hours.</b>
            </td>
            <td></td><td></td>
            <td></td><td></td>
        </tr>
        <tr>
            <td></td>
            <td></td><td></td>
            @for ($i = 0; $i < count($dates); $i++)
            <td></td>
            @endfor
            <td></td><td></td><td></td><td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td><td colspan="3">PREPARED BY:</td>
            <td colspan="{{floor(count($dates)/3)}}" class="text-center">
                RECIEVED BY:
            </td>
            <td colspan="{{floor(count($dates)/3)}}" class="text-center">
                RECEIVED/APPROVED BY:
            </td>
            <td colspan="{{floor(count($dates)/3)}}" class="text-center">
                RECEIVED/APPROVED BY:
            </td>
            <td></td><td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td><td colspan="3"><b>T/LEADER:</b></td>
            <td colspan="{{floor(count($dates)/3)}}" class="text-center">
                <b>GROUP LEADER</b>
            </td>
            <td colspan="{{floor(count($dates)/3)}}" class="text-center">
                <b>MANAGER</b>
            </td>
            <td colspan="{{floor(count($dates)/3)}}" class="text-center">
                <b>DIRECTOR, HUMAN RESOURCE AND ADMINISTRATION</b>
            </td>
            <td></td><td></td>
        </tr>

        <tr>
            <td></td>
            <td></td><td></td>
            @for ($i = 0; $i < count($dates); $i++)
            <td></td>
            @endfor
            <td></td><td></td><td></td><td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td><td colspan="3">{{$tlname}}</td>
            <td colspan="{{floor(count($dates)/3)}}" class="text-center">
               SIGN ...........................................................
            </td>
            <td colspan="{{floor(count($dates)/3)}}" class="text-center">
                SIGN ...........................................................
            </td>
            <td colspan="{{floor(count($dates)/3)}}" class="text-center">
                SIGN ...........................................................
            </td>
            <td></td><td></td>
        </tr>


        <tr>
            <td></td>
            <td></td><td></td>
            @for ($i = 0; $i < count($dates); $i++)
            <td></td>
            @endfor
            <td></td>
            <td></td><td></td><td></td>
        </tr>

    </tbody>

</table>
