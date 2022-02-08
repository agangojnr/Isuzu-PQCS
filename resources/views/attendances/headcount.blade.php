@include('layouts.header.reportheader')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <ol class="breadcrumb mb-2  bg-grey">
                            <li class="breadcrumb-item">
                                <h3 class="card-title"><u>HOURS WORKED
                                    <span style="text-transform: uppercase;"><br> BETWEEN
                                    ({{$range}})</span></u></h3>
                            </li>
                        </ol>

                    </div>
                    <div class="col-6">
                        {!! Form::open(['action'=>'App\Http\Controllers\attendance\AttendanceController@headcount',
                         'method'=>'post', 'enctype' => 'multipart/form-data']); !!}

                        <div class="row">
                            <div class="col-7">
                                <h4 class="card-title">Choose Date Range:</h4>
                                <div class='input-group'>
                                    <!--<input type='text' name="mdate" class="form-control singledate" />-->
                                    <input type='text' name="daterange" class="form-control shawCalRanges" />

                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <span class="ti-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <div class="col-5">
                            <button type="submit" class="btn btn-success mt-4">Filter HC</button>
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
                                    <th>Shop</th>
                                    <th>Direct HC</th>
                                    <th>Indirect HC</th>
                                    <th>Total HC</th>
                                    <th>Prodn Hrs</th>
                                    <th>Total Hours Worked</th>
                                    <th>Efficiency</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shops as $shop)
                                <tr>
                                    <td>{{$shop->report_name}}</td>
                                    <td>{{$ttheadcount[$shop->id] - $countTL[$shop->id]}}</td>
                                    <td>{{$countTL[$shop->id]}}</td>
                                    <td>{{$ttheadcount[$shop->id]}}</td>
                                    <td>{{round($MTDPrdnhrs[$shop->id],2)}}</td>
                                    <td>{{round($MTDtthrs[$shop->id],2)}}</td>
                                    <td>{{($spMTDplant_eff[$shop->id]=="--") ? "--" :round($spMTDplant_eff[$shop->id],2)."%"}}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <th>Plant</th>
                                    <th>{{$tthc - $tttl}}</th>
                                    <th>{{$tttl}}</th>
                                    <th>{{$tthc}}</th>
                                    <th>{{$TTprdnhrs}}</th>
                                    <th>{{$AllTThrs}}</th>
                                    <th>{{round($MTDplant_eff,2)}}%</th>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Shop</th>
                                    <th>Direct HC</th>
                                    <th>Indirect HC</th>
                                    <th>Total HC</th>
                                    <th>Prodn Hrs</th>
                                    <th>Total Hours Worked</th>
                                    <th>Efficiency</th>
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
    {{ Html::script('js/jquery-1.11.0.min.js') }}
    {{ Html::script('assets/libs/moment/moment.js') }}
    {{ Html::script('assets/libs/select2/dist/js/select2.full.min.js') }}
    {{ Html::script('assets/libs/select2/dist/js/select2.min.js') }}
    {{ Html::script('assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
    {{ Html::script('assets/libs/daterangepicker/daterangepicker.js') }}

    <script>
        $(function(){
        'use strict'
        $('.shawCalRanges').daterangepicker({
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
                alwaysShowCalendars: true,
            });
        });
    </script>
    {!! Toastr::message() !!}

