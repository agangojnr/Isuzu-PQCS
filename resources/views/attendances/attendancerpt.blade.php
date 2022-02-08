@include('layouts.header.reportheader')
    <div class="row ">

    </div>
    <!-- End Row -->

    <div class="container-fluid">

    <!-- Individual column searching (select inputs) -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <ol class="breadcrumb mb-2  bg-grey">
                                <li class="breadcrumb-item">
                                    <h3 class="card-title"><u>HOURS WORKED LAST 30 DAYS REPORT FOR <br><span style="text-transform: uppercase;">{{$shopname}}</span> IN
                                        ({{\Carbon\Carbon::createFromFormat('Y-m-d', $today)->format('M Y')}})</u></h3>
                                </li>
                            </ol>

                        </div>
                        <div class="col-7">
                            {!! Form::open(['action'=>'App\Http\Controllers\attendance\AttendanceController@attendancereport',
                            'method'=>'post', 'enctype' => 'multipart/form-data']); !!}
                            <div class="row">
                                <div class="col-4">
                                    <h4 class="card-title">Choose Section:</h4>
                                    <div class='input-group'>
                                        <select name="shop" class="form-control select2" required>
                                            <option value="">Select section...</option>
                                            @foreach ($selectshops as $item)
                                                <option value="{{$item->id}}">{{$item->report_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <h4 class="card-title">Choose Month:</h4>
                                    <div class='input-group'>
                                        <input type="text" name="mdate" id="datepicker" class="form-control bg-white" readonly
                                        value="{{\Carbon\Carbon::createFromFormat('Y-m-d', $today)->format('F Y')}}" autocomplete="off" />

                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <span class="ti-calendar"></span>
                                            </span>
                                        </div>
                                    </div>

                            </div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-success mt-4">Filter Hours</button>
                            </div>

                            </div>
                            {{Form::hidden('_method', 'GET')}}
                            {!! Form::close() !!}

                        </div>
                    </div>



                    <div class="table-responsive">
                        <table class="tablesaw table-bordered table-hover table no-wrap" data-tablesaw-mode="swipe"
                                    data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap
                                    data-tablesaw-mode-switch>
                            <thead>
                                <th>STAFF NO</th>
                                <th>STAFF NAME</th>
                                @for ($i = 0; $i < $count; $i++)
                                    <th>{{$dates[$i]}}</th>
                                @endfor
                                <th>TOTAL</th>
                                <th>STAFF NO</th>
                            </thead>
                            <tbody>
                                @foreach ($employees as $emp)
                                <tr>
                                    <td>{{$emp->staff_no}}</td>
                                    <td>{{$emp->staff_name}}</td>
                                    @for ($i = 0; $i < $count; $i++)
                                        <td>{{round($emphrs[$emp->id][$i],1)}}</td>
                                    @endfor
                                    <th>{{round($ttemphrs[$emp->id],2)}}</th>
                                    <td>{{$emp->staff_no}}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td>TOTAL</td>
                                    @for ($i = 0; $i < $count; $i++)
                                        <th>{{round($hrsperdate[$i],2)}}</th>
                                    @endfor
                                    <th>{{round($ttsum,2)}}</th>
                                    <th></th>
                                </tr>
                            </tbody>
                            <tfoot>
                                <th>STAFF NO</th>
                                <th>STAFF NAME</th>
                                @for ($i = 0; $i < $count; $i++)
                                    <th>{{$dates[$i]}}</th>
                                @endfor
                                <th></th>
                                <th>STAFF NO</th>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer.script')
    @yield('after-scripts')
    @yield('extra-scripts')
    {{ Html::script('dist/js/pages/datatable/datatable-basic.init.js') }}
    {{ Html::style('assets/libs/select2/dist/css/select2.min.css') }}


     {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
     {{ Html::script('assets/libs/select2/dist/js/select2.full.min.js') }}
     {{ Html::script('assets/libs/select2/dist/js/select2.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
    {{ Html::script('assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}

    <script type="text/javascript">
        $(".select2").select2();
    </script>
    <script>
        $(function(){
        var today = new Date();
        $("#datepicker").datepicker({
            showDropdowns: true,
            format: "MM yyyy",
            viewMode: "years",
            minViewMode: "months",
            maxDate: today,
            });
        });
    </script>
    {!! Toastr::message() !!}

