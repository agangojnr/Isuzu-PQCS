<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Standard Working Hours</title>

    @include('layouts.header.header')
    @yield('after-styles')

<link rel="stylesheet" type="text/css" href="{{asset('assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">

</head>
<body>

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles" style="background-color:#da251c; ">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-white mb-0">DAILY ATTENDANCE REPORT</h3>
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
                            {!! Form::open(['action'=>'App\Http\Controllers\attendance\AttendanceController@attendceregister',
                            'method'=>'post', 'enctype' => 'multipart/form-data']); !!}
                            <div class="row">
                                <div class="col-4">
                                @if(shop() == "noshop")
                                    <h4 class="card-title">Choose Section:</h4>
                                    <div class='input-group'>
                                        <select name="shop" class="form-control select2" required>
                                            <option value="">Select section...</option>
                                            @foreach ($selectshops as $item)
                                                <option value="{{$item->id}}">{{$item->report_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                <div class="form-group ml-5" style="font-size: 18px;">
                                    <input type="hidden" name="shop" value="{{Auth()->User()->section}}">
                                    {!! Form::label('name', 'Section:*') !!}
                                    {!! Form::label('name', \App\Models\shop\Shop::where('id','=',Auth()->User()->section)->value('report_name'), ['class' => 'font-weight-bold']); !!}
                                </div>
                                @endif
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
                                <button type="submit" class="btn btn-success mt-4">Apply Filter</button>

                            </div>

                            </div>
                            {{Form::hidden('_method', 'GET')}}
                            {!! Form::close() !!}


                            <div class="row mb-1">
                                <div class="col-6"></div>
                                <div class="col-3">
                            {{ Form::open(['route' => 'exportattendRegister', 'method' => 'GET'])}}
                            {{ csrf_field(); }}
                            <input type="hidden" name="mdate" value="{{\Carbon\Carbon::createFromFormat('Y-m-d', $today)->format('F Y');}}">
                            <input type="hidden" name="shop" value="{{$shopid}}">
                                <button style="background-color:teal; color:white;"
                                    class="btn btn-md mt-4 mr-2" ><i class="glyphicon glyphicon-edit"></i>Export to Excel</button>
                            {!! Form::close(); !!}
                            </div>
                            <div class="col-3">
                            {{ Form::open(['route' => 'attendRegisterpdf', 'method' => 'GET'])}}
                            {{ csrf_field(); }}
                            <input type="hidden" name="mdate" value="{{\Carbon\Carbon::createFromFormat('Y-m-d', $today)->format('F Y');}}">
                            <input type="hidden" name="shop" value="{{$shopid}}">
                                <button style="background-color:rgb(0, 163, 82); color:white;"
                                class="btn btn-md mt-4" ><i class="glyphicon glyphicon-edit"></i>Download PDF</button>
                            {!! Form::close(); !!}
                                </div>
                            </div>


                        </div>
                    </div>


                    <div class="ContenedorTabla">
                       @include('attendances.attendanceregister_table')
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{ Html::style('assets/libs/select2/dist/css/select2.min.css') }}
    {{ Html::style('css/ScrollTabla.css') }}

    {{ Html::script('js/jquery-1.11.0.min.js') }}
    {{ Html::script('assets/libs/moment/moment.js') }}
     {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
     {{ Html::script('assets/libs/select2/dist/js/select2.full.min.js') }}
     {{ Html::script('assets/libs/select2/dist/js/select2.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
    {{ Html::script('assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}
    {{ Html::script('js/jquery.CongelarFilaColumna.js') }}

    <script type="text/javascript">
        $(".select2").select2();

        $(document).ready(function(){
			$("#pruebatabla").CongelarFilaColumna({Columnas:2,coloreacelda:true});
		});
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

