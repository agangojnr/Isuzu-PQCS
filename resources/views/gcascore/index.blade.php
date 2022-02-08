
@extends('layouts.app')
@section('title','GCA Score')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">GCA Score</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Daily GCA Score</li>
            </ol>
        </div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <div class="d-flex mt-2 justify-content-end">
                <div class="d-flex mr-3 ml-2">
                    <div class="chart-text mr-2">
                        <h6 class="mb-0"><small>THIS MONTH</small></h6>
                        <h4 class="mt-0 text-info">$58,356</h4>
                    </div>
                    <div class="spark-chart">
                        <div id="monthchart"></div>
                    </div>
                </div>
                <div class="d-flex ml-2">
                    <div class="chart-text mr-2">
                        <h6 class="mb-0"><small>LAST MONTH</small></h6>
                        <h4 class="mt-0 text-primary">$48,356</h4>
                    </div>
                    <div class="spark-chart">
                        <div id="lastmonthchart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
<div class="container-fluid">
  <div class="content-header row pb-1">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="mb-0">GCA Score</h3>

                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div class="media width-250 float-right">

                        <div class="media-body media-right text-right">
                            @include('gcascore.partial.gca-buttons')
                        </div>
                    </div>
                </div>
            </div>

    <!-- Individual column searching (select inputs) -->
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    {!! Form::open(['action'=>'App\Http\Controllers\gcascore\GcaScoreController@index',
                            'method'=>'post', 'enctype' => 'multipart/form-data']); !!}
                    <div class="row">

                        <div class="col-5 mb-2">
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
                        <div class="col-2">
                            <button type="submit" class="btn btn-primary mt-4">Filter Hours</button>
                        </div>

                    </div>
                    {{Form::hidden('_method', 'GET')}}
                        {!! Form::close() !!}

                    <div class="table-responsive" >
                        <table class="table table-striped table-bordered datatable-select-inputs w-100">
                            <thead>
                                <tr>
                                    <th rowspan="2">No.</th>
                                    <th rowspan="2">Name</th>
                                    @for ($i = 0; $i < count($dates); $i++)
                                        <th>{{$dayname[$i]}}</th>
                                    @endfor
                                    <th rowspan="2">MTD</th>
                                </tr>
                                <tr>


                                    @for ($i = 0; $i < count($dates); $i++)
                                        <th>{{$dates[$i]}}</th>
                                    @endfor

                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td rowspan="3">CV</td>
                                    <th>WDPV</th>
                                    @for ($i = 0; $i < count($dates); $i++)
                                        <td>{{$cv_wdpvscore[$i]}}</td>
                                    @endfor
                                    <th>{{$cv_MTDwdpv}}</th>
                                </tr>

                                <tr>

                                    <th>DPV</th>
                                    @for ($i = 0; $i < count($dates); $i++)
                                        <td>{{$cv_dpvscore[$i]}}</td>
                                    @endfor
                                    <th>{{$cv_MTDdpv}}</th>
                                </tr>
                                <tr>

                                    <th>Sample<br>Size</th>
                                    @for ($i = 0; $i < count($dates); $i++)
                                        <td>{{$cv_samplesize[$i]}}</td>
                                    @endfor
                                    <th>{{$cv_MTDsamplesize}}</th>
                                </tr>

                                <tr>
                                    <td rowspan="3">LCV</td>
                                    <th>WDPV</th>
                                    @for ($i = 0; $i < count($dates); $i++)
                                        <td>{{$lcv_wdpvscore[$i]}}</td>
                                    @endfor
                                    <th>{{$lcv_MTDwdpv}}</th>
                                </tr>

                                <tr>

                                    <th>DPV</th>
                                    @for ($i = 0; $i < count($dates); $i++)
                                        <td>{{$lcv_dpvscore[$i]}}</td>
                                    @endfor
                                    <th>{{$lcv_MTDdpv}}</th>
                                </tr>
                                <tr>

                                    <th>Sample<br>Size</th>
                                    @for ($i = 0; $i < count($dates); $i++)
                                        <td>{{$lcv_samplesize[$i]}}</td>
                                    @endfor
                                    <th>{{$lcv_MTDsamplesize}}</th>
                                </tr>

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('after-styles')
    {{ Html::style('assets/libs/select2/dist/css/select2.min.css') }}
    {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
    {{ Html::style('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}
@endsection

@section('after-scripts')

{{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
{{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
{{ Html::script('assets/libs/sweetalert2/dist/sweetalert2.all.min.js') }}
{{ Html::script('assets/libs/select2/dist/js/select2.full.min.js') }}
 {{ Html::script('assets/libs/select2/dist/js/select2.min.js') }}
 {{ Html::script('assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}
    {{ Html::script('assets/libs/daterangepicker/daterangepicker.js') }}
 {!! Toastr::message() !!}

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

    @endsection
