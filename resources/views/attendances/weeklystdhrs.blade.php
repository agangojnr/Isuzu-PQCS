@include('layouts.header.reportheader')
    <!-- End Row -->

    <div class="container-fluid">

    <!-- Individual column searching (select inputs) -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <ol class="breadcrumb mb-2  bg-grey">
                                <li class="breadcrumb-item">
                                    <h3 class="card-title"><u>WEEKLY GENERATED STD HOURS  FOR
                                        ({{\Carbon\Carbon::createFromFormat('Y-m-d', $todate)->format('M j Y')}})</u></h3>
                                </li>
                            </ol>

                        </div>
                        <div class="col-6">
                            {!! Form::open(['action'=>'App\Http\Controllers\attendance\AttendanceController@weeklystdhrs',
                            'method'=>'post', 'enctype' => 'multipart/form-data']); !!}
                            <div class="row">
                                <div class="col-7">
                                    <h4 class="card-title">Filter HC by Date</h4>
                                <div class='input-group'>
                                    <input type='text' name="mdate" class="form-control singledate" />

                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <span class="ti-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-5">
                                <button type="submit" class="btn btn-success mt-4">Filter Hours</button>
                            </div>

                            </div>
                            {{Form::hidden('_method', 'GET')}}
                            {!! Form::close() !!}

                        </div>
                    </div>



                    <table class="tablesaw table-bordered table-hover table no-wrap" data-tablesaw-mode="swipe"
                                    data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap
                                    data-tablesaw-mode-switch>
                            <thead>
                                <tr>
                                    <th rowspan="3">SECTION</th>

                                    <th colspan="2">MON</th>
                                    <th colspan="2">TUE</th>
                                    <th colspan="2">WED</th>
                                    <th colspan="2">THUR</th>
                                    <th colspan="2">FRI</th>
                                    <th colspan="2">SAT</th>
                                    <th colspan="2">SUN</th>
                                    <th rowspan="2" colspan="2">WEEKLY TOTAL</th>

                                </tr>
                                <tr>

                                    <th colspan="2">{{$dates[0]}}</th>
                                    <th colspan="2">{{$dates[1]}}</th>
                                    <th colspan="2">{{$dates[2]}}</th>
                                    <th colspan="2">{{$dates[3]}}</th>
                                    <th colspan="2">{{$dates[4]}}</th>
                                    <th colspan="2">{{$dates[5]}}</th>
                                    <th colspan="2">{{$dates[6]}}</th>
                                </tr>
                                <tr>
                                    <th>ACT</th>
                                    <th>STD</th>
                                    <th>ACT</th>
                                    <th>STD</th>
                                    <th>ACT</th>
                                    <th>STD</th>
                                    <th>ACT</th>
                                    <th>STD</th>
                                    <th>ACT</th>
                                    <th>STD</th>
                                    <th>ACT</th>
                                    <th>STD</th>
                                    <th>ACT</th>
                                    <th>STD</th>
                                    <th>ACT</th>
                                    <th>STD</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shops as $shop)
                                    <tr>
                                        <td>{{$shop->report_name}}</td>

                                            <td>{{$actualhrs[$shop->id][0]}}</td>
                                            <td>{{$stdhrs[$shop->id][0]}}</td>
                                            <td>{{$actualhrs[$shop->id][1]}}</td>
                                            <td>{{$stdhrs[$shop->id][1]}}</td>
                                            <td>{{$actualhrs[$shop->id][2]}}</td>
                                            <td>{{$stdhrs[$shop->id][2]}}</td>
                                            <td>{{$actualhrs[$shop->id][3]}}</td>
                                            <td>{{$stdhrs[$shop->id][3]}}</td>
                                            <td>{{$actualhrs[$shop->id][4]}}</td>
                                            <td>{{$stdhrs[$shop->id][4]}}</td>
                                            <td>{{$actualhrs[$shop->id][5]}}</td>
                                            <td>{{$stdhrs[$shop->id][5]}}</td>
                                            <td>{{$actualhrs[$shop->id][6]}}</td>
                                            <td>{{$stdhrs[$shop->id][6]}}</td>
                                            <th>{{$weekactualhrs[$shop->id]}}</th>
                                            <th>{{$weekstdhrs[$shop->id]}}</th>


                                        </tr>

                                @endforeach


                            </tbody>
                            <tfoot>
                                <tr>
                                    <th rowspan="3">SECTION</th>

                                    <th colspan="2">MON</th>
                                    <th colspan="2">TUE</th>
                                    <th colspan="2">WED</th>
                                    <th colspan="2">THUR</th>
                                    <th colspan="2">FRI</th>
                                    <th colspan="2">SAT</th>
                                    <th colspan="2">SUN</th>
                                    <th rowspan="2" colspan="2">WEEKLY TOTAL</th>

                                </tr>
                                <tr>

                                    <th colspan="2">{{$dates[0]}}</th>
                                    <th colspan="2">{{$dates[1]}}</th>
                                    <th colspan="2">{{$dates[2]}}</th>
                                    <th colspan="2">{{$dates[3]}}</th>
                                    <th colspan="2">{{$dates[4]}}</th>
                                    <th colspan="2">{{$dates[5]}}</th>
                                    <th colspan="2">{{$dates[6]}}</th>
                                </tr>
                                <tr>
                                    <th>ACT</th>
                                    <th>STD</th>
                                    <th>ACT</th>
                                    <th>STD</th>
                                    <th>ACT</th>
                                    <th>STD</th>
                                    <th>ACT</th>
                                    <th>STD</th>
                                    <th>ACT</th>
                                    <th>STD</th>
                                    <th>ACT</th>
                                    <th>STD</th>
                                    <th>ACT</th>
                                    <th>STD</th>
                                    <th>ACT</th>
                                    <th>STD</th>


                                </tr>
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

     {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}

    {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
    {!! Toastr::message() !!}

