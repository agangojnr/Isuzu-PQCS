<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Performance</title>

    @include('layouts.header.header')
    @yield('after-styles')

    {{ Html::style('assets/libs/select2/dist/css/select2.min.css') }}
<link rel="stylesheet" type="text/css" href="{{asset('assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">

</head>
<body>

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">PLANT PERFORMANCE TRACKING</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">REPORTS</li>
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
                    <a href="/home" id="btn-add-contact" class="btn btn-danger float-right"
                style="background-color:#da251c; "><i class="mdi mdi-arrow-left font-16"></i> Back to Home</a>
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
                        <div class="col-5">
                            <ol class="breadcrumb mb-2  bg-grey">
                                <li class="breadcrumb-item">
                                    <h3 class="card-title"><u>PLANT PERFORMANCE TRACKING REPORT  FOR<br><span style="text-transform: uppercase;"></span>
                                        ({{\Carbon\Carbon::createFromFormat('Y-m-d', $today)->format('M Y')}})</u></h3>
                                </li>
                            </ol>

                        </div>
                        <div class="col-7">
                            {!! Form::open(['action'=>'App\Http\Controllers\graph\GraphController@trackperformance',
                            'method'=>'post', 'enctype' => 'multipart/form-data']); !!}
                            <div class="row">

                                <div class="col-4">
                                    <h4 class="card-title">Choose Month:</h4>
                                    <div class='input-group'>
                                        <input type="text" name="mdate" id="datepicker" class="form-control bg-white" readonly
                                        value="{{\Carbon\Carbon::createFromFormat('Y-m-d', "2021-11-10")->format('F Y')}}" autocomplete="off" />

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
                            <tr style="background-color:rgb(252, 248, 8);">
                                <th rowspan="2">SHOP</th>
                                <th rowspan="2"></th>
                                <th></th>
                                @for ($i = 0; $i < count($dayname); $i++)
                                    <th>{{$dayname[$i]}}</th>
                                @endfor

                            </tr>
                            <tr style="background-color:rgb(252, 248, 8);">
                                <th></th>
                                @for ($i = 0; $i < count($dayname); $i++)
                                    <th>{{$dates[$i]}}</th>

                                @endfor
                            </tr>


                            <!--Table body-->
                            <tbody style="color: black; font-weight:bold;">
                                @foreach ($shopnames as $shop)
                                    <tr style="background-color:rgb(199, 228, 204);">
                                        <th rowspan="6">{{$shop->report_name}}</th>
                                        <th rowspan="3" style="writing-mode: vertical-rl;text-orientation: mixed;">
                                            Daily</th>
                                        <th style="background-color:rgb(211, 217, 236);">Forecast</th>
                                        @for ($i = 0; $i < count($dayname); $i++)
                                            <td style="background-color:rgb(211, 217, 236);">
                                                {{$countsched[$shop->id][$i]}}</td>
                                        @endfor
                                    </tr>
                                    <tr style="background-color:rgb(211, 217, 236);">
                                        <th>Actual</th>
                                        @for ($i = 0; $i < count($dayname); $i++)
                                        <td>{{$actual[$shop->id][$i]}}</td>
                                        @endfor
                                    </tr>
                                    <tr>
                                        <th style="background-color:rgb(211, 217, 236);">Variance</th>
                                        @for ($i = 0; $i < count($dayname); $i++)
                                        @if($varience[$shop->id][$i] < 0)
                                        <td style="background-color:rgb(250, 10, 10);">{{$varience[$shop->id][$i]}}</td>
                                        @elseif($varience[$shop->id][$i] == '')
                                        <td style="background-color:rgb(211, 217, 236)">{{$varience[$shop->id][$i]}}</td>
                                        @else
                                        <td style="background-color:rgb(36, 173, 116);">{{$varience[$shop->id][$i]}}</td>
                                        @endif

                                        @endfor
                                    </tr>

                                    <tr style="background-color:rgb(199, 228, 204);">
                                        <th rowspan="3" style="writing-mode: vertical-rl;text-orientation: mixed;">
                                            MTD</th>
                                        <th>Forecast</th>
                                        @for ($i = 0; $i < count($dayname); $i++)
                                            <td>{{$allMTDfcst[$shop->id][$i]}}</td>
                                        @endfor
                                    </tr>
                                    <tr style="background-color:rgb(214, 246, 246);">
                                        <th>Actual</th>
                                        @for ($i = 0; $i < count($dayname); $i++)
                                        <td>{{$allMTDactual[$shop->id][$i]}}</td>
                                        @endfor
                                    </tr>
                                    <tr style="background-color:rgb(214, 246, 246);">
                                        <th>Variance</th>
                                        @for ($i = 0; $i < count($dayname); $i++)
                                            @if($allVarie[$shop->id][$i] < 0)
                                                <td>{{$allVarie[$shop->id][$i]}}</td>
                                            @elseif($allVarie[$shop->id][$i] == '')
                                                <td style="background-color:rgb(214, 246, 246);">{{$allVarie[$shop->id][$i]}}</td>
                                            @else
                                                <td style="background-color:rgb(36, 173, 116);">{{$allVarie[$shop->id][$i]}}</td>
                                            @endif
                                        @endfor
                                    </tr>
                                @endforeach
                            </tbody>


                            <!--Table footer-->
                            <tfoot style="background-color:rgb(252, 248, 8);">
                                <th>SHOP</th>
                                <th></th>
                                <th></th>
                                @for ($i = 0; $i < count($dayname); $i++)
                                    <th>{{$dayname[$i]}}</th>
                                @endfor

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
    @section('after-styles')
    {{ Html::script('dist/js/pages/datatable/datatable-basic.init.js') }}

    <style type="text/css">
        .table-bordered{
            border: 1px solid black;
        }
    </style>



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

