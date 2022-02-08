<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Overtime</title>

    @include('layouts.header.header')
    @yield('after-styles')

    {{ Html::style('assets/libs/select2/dist/css/select2.min.css') }}
<link rel="stylesheet" type="text/css" href="{{asset('assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">

</head>
<body>

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles" style="background-color:#da251c;">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-white mb-0">MONTHLY OVERTIME HOURS REPORT PER SHOP</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active text-white">REPORTS</li>
            </ol>

        </div>
        <div class="col-md-7">
            <div class="row float-left w-100">
                <div class="col-lg-7">
                    <span  class="btn waves-effect waves-light btn-lg"
                    style="background-color: #DAF7A6; opacity: 0.9; font-familiy:Times New Roman;">

                    <h5 class="float-right mt-2">{{\Carbon\Carbon::today()->format('j M Y')}}</h5></span>
                </div>
                <div class="col-5">
                    <a href="/home" id="btn-add-contact" class="btn btn-primary float-right"
               ><i class="mdi mdi-arrow-left font-16"></i> Back to Home</a>
                </div>
            </div>

        </div>
    </div>
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
                        <div class="col-4">
                            <ol class="breadcrumb mb-2  bg-grey">
                                <li class="breadcrumb-item">
                                    <h3 class="card-title"><u>OVERTIME REPORT FOR
                                        <span style="text-transform: uppercase;">{{$shopname}}<br> BETWEEN
                                        ({{$range}})</span></u></h3>
                                </li>
                            </ol>

                        </div>
                        <div class="col-8">
                            {!! Form::open(['action'=>'App\Http\Controllers\overtime\OvertimeController@overtimereport',
                            'method'=>'post', 'enctype' => 'multipart/form-data']); !!}
                            <div class="row">
                                <div class="col-5">
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
                                @if(shop() == "noshop")
                                    <h4 class="card-title">Choose Section:</h4>
                                    <div class='input-group'>
                                        {{Form::select('shop', $selectshops, $selectshops->pluck('id'), array('class'=>'form-control select2', 'placeholder'=>'Please select ...', 'required'=>'required',
                                        'style'=>'width: 100%;'))}}
                                    </div>
                                @else
                                 <div class="form-group ml-5" style="font-size: 18px;">
                                    <input type="hidden" name="shop" value="{{Auth()->User()->section}}">
                                    {!! Form::label('name', 'Section:*') !!}
                                    {!! Form::label('name', \App\Models\shop\Shop::where('id','=',Auth()->User()->section)->value('report_name'), ['class' => 'font-weight-bold']); !!}
                                </div>
                                @endif
                                </div>


                            <div class="col-2">
                                <button type="submit" class="btn btn-success mt-4">Filter Hours</button>
                            </div>

                            </div>
                            {{Form::hidden('_method', 'GET')}}
                            {!! Form::close() !!}

                        </div>

                    </div>
                    {{ Form::open(['route' => 'exporttoexcel', 'method' => 'GET'])}}
                    {{ csrf_field(); }}
                    <div class="row mb-1">
                        <div class="col-9"></div>
                        <div class="col-3">
                            <input type="hidden" name="range" value="{{$daterange}}">
                            <input type="hidden" name="shop" value="{{$shopid}}">
                            @if ($otunauth == 1)
                                <h4 class="text-danger">Sorry,Can't Print. Pending Authorization!</h4>
                            @else
                            <button style="background-color:teal; color:white;" href="{{route('exporttoexcel')}}"
                            class="btn btn-md  float-right" ><i class="glyphicon glyphicon-edit"></i>Export to Excel</button>
                            @endif
                        </div>
                    </div>
                    {!! Form::close(); !!}
                    <div class="ContenedorTabla">
                        @include('overtime.otreport_table')
                    </div>
                </div>


            </div>
        </div>
    </div>


     {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
     {{ Html::style('css/ScrollTabla.css') }}


    @section('after-scripts')
    {{ Html::script('js/jquery-1.11.0.min.js') }}
    {{ Html::script('assets/libs/moment/moment.js') }}
    {{ Html::script('assets/libs/select2/dist/js/select2.full.min.js') }}
    {{ Html::script('assets/libs/select2/dist/js/select2.min.js') }}
    {{ Html::script('assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
    {{ Html::script('assets/libs/daterangepicker/daterangepicker.js') }}
    {{ Html::script('js/jquery.CongelarFilaColumna.js') }}
    {!! Toastr::message() !!}

    <script type="text/javascript">
        $(".select2").select2();
    </script>

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

        $(document).ready(function(){
			$("#pruebatabla").CongelarFilaColumna({Columnas:3,coloreacelda:true});
		});
    </script>
    {!! Toastr::message() !!}

